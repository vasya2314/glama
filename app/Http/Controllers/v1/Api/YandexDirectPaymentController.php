<?php

namespace App\Http\Controllers\v1\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\YandexDirectPaymentRequest;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class YandexDirectPaymentController extends Controller
{
    public function deposit(YandexDirectPaymentRequest $request)
    {
        $data = $request->validated();
        $client = Client::findOrFail($data['client_id']);

        $param = [
            'Action' => 'Invoice',
            'Payments' => [
                [
                    "AccountID" => $client->client_id,
                    "Amount" => (float)kopToRub((int)$data['amount']),
                    "Currency" => 'RUB'
                ]
            ]
        ];

        $response = Http::withToken(config('yandex')['token'])->post('https://api-sandbox.direct.yandex.ru/live/v4/json/', [
            'method' => 'AccountManagement',
            'finance_token' => $this->generateFinanceToken($client),
            'operation_num' => 1,
            'param' => $param,
        ]);

        return $response->body();
    }

    private function generateFinanceToken(Client $client): string
    {
        $masterToken = config('yandex')['master_token'];
        $operationId = 1;
        $methodName = 'AccountManagement';
        $operationName = 'Invoice';
        $login = $client->login;

        $values = $masterToken . $operationId . $methodName . $operationName . $login;

        dd($values);

        return hash('sha256', $values);
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
