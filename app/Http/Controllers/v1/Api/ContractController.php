<?php

namespace App\Http\Controllers\v1\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\ContractRequest;
use App\Http\Resources\v1\ContractResource;
use App\Models\Contract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ContractController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if($request->has('short_query'))
        {
            $contracts = $user->contracts()->select('id', 'display_name', 'user_id')->get();

            $contracts = ContractResource::collection($contracts)->response()->getData(true);
            return $this->wrapResponse(Response::HTTP_OK, __('All contracts.'), (array)$contracts);
        }

        $contracts = $user->contracts;
        $contracts = ContractResource::collection($contracts)->response()->getData(true);

        return $this->wrapResponse(Response::HTTP_OK, __('All contracts.'), (array)$contracts);
    }

    public function store(ContractRequest $request): JsonResponse
    {
        $user = $request->user();
        $contract = null;

        DB::transaction(function () use ($request, $user, &$contract) {
            $contract = $user->contracts()->create($request->storeContract());
        });

        $contract = (new ContractResource($contract))
            ->response()
            ->getData(true);

        return $this->wrapResponse(Response::HTTP_CREATED, __('Contract created successfully.'), $contract);
    }

    public function show(Contract $contract): JsonResponse
    {
        Gate::authorize('view', $contract);

        $contractResource = (new ContractResource($contract))
            ->response()
            ->getData(true);

        return $this->wrapResponse(Response::HTTP_OK, __('Detail contract'), $contractResource);
    }

    /**
     * @throws \ErrorException
     */
    public function update(ContractRequest $request, Contract $contract): JsonResponse
    {
        Gate::authorize('update', $contract);

        DB::transaction(function () use ($request, &$contract) {
            $contract->update($request->updateContract());
        });

        $contract = (new ContractResource($contract))
            ->response()
            ->getData(true);

        return $this->wrapResponse(Response::HTTP_OK, __('Contract updated successfully.'), $contract);
    }

//    public function delete(Contract $contract): JsonResponse
//    {
//        Gate::authorize('delete', $contract);
//
//        if($contract->delete()) return $this->wrapResponse(Response::HTTP_OK, __('Contract deleted successfully.'));
//
//        throw new \ErrorException(__('Failed to get service, please try again.'), Response::HTTP_INTERNAL_SERVER_ERROR);
//    }

    public function generatePdf(Request $request, Contract $contract)
    {
        Gate::authorize('view', $contract);

        $logo = base64_encode(file_get_contents(public_path('storage/static/glama_logo.png')));
        $template = view('pdf.contract', compact('contract', 'logo'))->render();

        $pdf = App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option('enable_php', true);
        $pdf->loadHTML($template);
        return $pdf->stream();
    }

    private function wrapResponse(int $code, string $message, ?array $resource = []): JsonResponse
    {
        $result = [
            'code' => $code,
            'message' => $message
        ];

        if (count($resource)) $result = array_merge($result, ['resource' => $resource]);

        return response()->json($result, $code);
    }
}
