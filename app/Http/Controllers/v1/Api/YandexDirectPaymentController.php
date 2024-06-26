<?php

namespace App\Http\Controllers\v1\Api;

use App\Facades\YandexDirect;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\YandexDirectPaymentRequest;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class YandexDirectPaymentController extends Controller
{
    public function deposit(YandexDirectPaymentRequest $request)
    {
        $data = $request->validated();
        $client = Client::findOrFail($data['client_id']);

        return YandexDirect::deposit($client, $request);

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
