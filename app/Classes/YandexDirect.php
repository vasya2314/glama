<?php

namespace App\Classes;

use App\Models\Client;
use App\Models\Transaction;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class YandexDirect
{
    protected static string $token;
    protected static string $urlV4;
    protected static string $urlV5;
    protected static string $masterToken;
    protected static string $login;

    public function __construct()
    {
        $this->setProperties();
    }

    private function setProperties(): void
    {
        self::$masterToken = config('yandex')['master_token'];
        self::$login = config('yandex')['login'];
        self::$token = config('yandex')['token'];
        self::$urlV4 = env('YANDEX_API_LIVE_V4');
        self::$urlV5 = env('YANDEX_API');
    }

    public function getAllClients(array $params): ?object
    {
        $response = self::storeRequest(
            self::$urlV5 . 'agencyclients',
            [
                'method' => 'get',
                'params' => $params,
            ]
        );

        return $response->object();
    }

    public function storeClient(array $params): ?object
    {
        $response = self::storeRequest(
            self::$urlV5 . 'agencyclients',
            [
                'method' => 'add',
                'params' => $params,
            ]
        );

        return $response->object();
    }

//    public function enableSharedAccount(string $login)
//    {
//        $response = self::storeRequest(
//            self::$urlV4,
//            [
//                'method' => 'EnableSharedAccount',
//                'token' => self::$token,
//                'param' => [
//                    'Login' => $login
//                ],
//            ]
//        );
//
//        dd($response->object());
//    }

    public function clientHasActiveCampaigns()
    {

    }

    public function getClientCampaignsQty(string $clientLogin): JsonResponse|int
    {
        $data = [
            "method" => "get",
            "params" => [
                "SelectionCriteria" => [
                    "States" => [
                        "SUSPENDED",
                        "ON",
                        "OFF",
                    ],
                    "Statuses" => [
                        "MODERATION",
                        "ACCEPTED",
                    ],
                    "StatusesPayment" => [
                        "DISALLOWED",
                        "ALLOWED"
                    ]
                ],
                "FieldNames" => [
                    "Id",
                ],
            ]
        ];

        $response = self::storeRequest(self::$urlV5 . 'campaigns', $data, ['Client-Login' => $clientLogin]);

        if($this->hasErrors(json_decode($response->body())))
        {
            return \response()->json(
                [
                    json_decode($response->body())
                ],
                500
            );
        }

        return count(json_decode($response->body())->result->Campaigns);

    }

    public function deposit(array $params, Client $client, Request $request)
    {
        if(!$this->canDoDeposit($client))
        {
            return false;
        }

        $user = request()->user();

        $transaction = $user->transactions()->create(
            [
                'type' => Transaction::TYPE_DEPOSIT_YANDEX_ACCOUNT,
                'status' => Transaction::STATUS_NEW,
                'order_id' => Transaction::generateUUID(),
                'amount_deposit' => $request->get('amount'),
                'amount' => $request->get('amount'),
                'method_type' => 'yandex_invoice'
            ]
        );



//        $response = Http::post(self::$urlV4, [
//            'method' => 'AccountManagement',
//            'finance_token' => $this->generateFinanceToken('AccountManagement', $params['Action']),
//            'operation_num' => 1,
//            'token' => self::$token,
//            'param' => $params,
//        ]);
//
//        dd(json_decode($response->body()));

//        return $response->body();
    }
//
//    private function generateFinanceToken(string $action, string $method): string
//    {
//        $operation_num = 1;
//
//        return hash("sha256", self::$masterToken . $operation_num . $action . $method . self::$login);
//    }

    protected static function storeRequest(string $url, array $data, array $headers = []): PromiseInterface|Response
    {
        $baseHeaders = [
            'Accept-Language' => 'ru'
        ];

        $headers = array_merge($baseHeaders, $headers);
        return Http::withHeaders($headers)->withToken(self::$token)->post($url, $data);
    }

    protected function hasErrors(object $object): bool
    {
        return isset($object->error);
    }

    protected function canDoDeposit(Client $client): bool
    {
        if($client->qty_campaigns > 0 && $client->is_enable_shared_account == true)
        {
            return true;
        }

        return false;

    }

}
