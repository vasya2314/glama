<?php

namespace App\Http\Controllers\v1\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\RewardContractRequest;
use App\Http\Resources\v1\RewardContractResource;
use App\Models\RewardContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RewardContractController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if($request->has('short_query'))
        {
            $rewardContracts = $user->rewardContracts()->select('id', 'display_name', 'user_id')->get();

            $rewardContracts = RewardContractResource::collection($rewardContracts)->response()->getData(true);
            return $this->wrapResponse(Response::HTTP_OK, __('All contracts.'), (array)$rewardContracts);
        }

        $rewardContracts = $user->rewardContracts;
        $rewardContracts = RewardContractResource::collection($rewardContracts)->response()->getData(true);

        return $this->wrapResponse(Response::HTTP_OK, __('All contracts.'), (array)$rewardContracts);
    }

    public function store(RewardContractRequest $request): JsonResponse
    {
        $user = $request->user();
        $rewardContract = null;

        DB::transaction(function () use ($request, $user, &$rewardContract) {
            $rewardContract = $user->rewardContracts()->create($request->storeRewardContract());
        });

        $rewardContract = (new RewardContractResource($rewardContract))
            ->response()
            ->getData(true);

        return $this->wrapResponse(Response::HTTP_CREATED, __('Contract created successfully.'), $rewardContract);
    }

    public function show(RewardContract $rewardContract): JsonResponse
    {
        Gate::authorize('view', $rewardContract);

        $rewardContractResource = (new RewardContractResource($rewardContract))
            ->response()
            ->getData(true);

        return $this->wrapResponse(Response::HTTP_OK, __('Detail contract'), $rewardContractResource);
    }

    /**
     * @throws \ErrorException
     */
    public function update(RewardContractRequest $request, RewardContract $rewardContract): JsonResponse
    {
        Gate::authorize('update', $rewardContract);

        DB::transaction(function () use ($request, &$rewardContract) {
            $rewardContract->update($request->updateRewardContract());
        });

        $rewardContract = (new RewardContractResource($rewardContract))
            ->response()
            ->getData(true);

        return $this->wrapResponse(Response::HTTP_OK, __('Contract updated successfully.'), $rewardContract);
    }

//    public function delete(RewardContract $rewardContract): JsonResponse
//    {
//        Gate::authorize('delete', $rewardContract);
//
//        if($rewardContract->delete()) return $this->wrapResponse(Response::HTTP_OK, __('Contract deleted successfully.'));
//
//        throw new \ErrorException(__('Failed to get service, please try again.'), Response::HTTP_INTERNAL_SERVER_ERROR);
//    }

    public function generatePdf(Request $request, RewardContract $rewardContract)
    {
        Gate::authorize('view', $rewardContract);

        $logo = base64_encode(file_get_contents(public_path('storage/static/glama_logo.png')));
        $template = view('pdf.reward-contract', compact('rewardContract', 'logo'))->render();

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
