<?php

namespace App\Http\Controllers\v1\Api;

use App\Classes\YandexDirect;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\YandexDirectPaymentRequest;
use App\Models\Client;
use Illuminate\Http\JsonResponse;

class YandexDirectPaymentController extends Controller
{
    public function deposit(YandexDirectPaymentRequest $request)
    {
        $data = $request->validated();
//        $client = Client::findOrFail($data['client_id']);

        $param = [
            'Action' => 'Invoice',
            'Payments' => [
                [
                    "AccountID" => 469264,
                    "Amount" => 10000.00,
                    "Currency" => 'RUB'
                ]
            ]
        ];

        $yandexDirect = new YandexDirect();
//        $yandexDirect->createInvoice($param, $client);
        $yandexDirect->createInvoice($param);

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
