<?php

namespace App\Http\Controllers\v1\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\ClosingDocumentResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
