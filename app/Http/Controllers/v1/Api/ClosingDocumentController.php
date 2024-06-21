<?php

namespace App\Http\Controllers\v1\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\ClosingDocumentRequest;
use App\Http\Requests\v1\UserRequest;
use App\Http\Resources\v1\ClosingDocumentResource;
use App\Models\ClosingAct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class ClosingDocumentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $closingDocuments = $user->closingDocuments()->latest()->paginate(20);
        $closingDocuments = ClosingDocumentResource::collection($closingDocuments)->response()->getData(true);

        return $this->wrapResponse(Response::HTTP_OK, __('All closing documents.'), (array)$closingDocuments);
    }

    public function generateClosingActPdf(ClosingDocumentRequest $request)
    {
        $data = $request->validated();

        $closingAct = ClosingAct::findOrFail($data['closing_act_id']);
        $closingDocument = $closingAct->closingDocument;
        $contract = $closingAct->contract;

        $logo = base64_encode(file_get_contents(public_path('storage/static/glama_logo.png')));
        $template = view('pdf.closing-act', compact('closingAct', 'logo', 'closingDocument', 'contract'))->render();

        $pdf = App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
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
