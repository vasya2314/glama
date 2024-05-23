<?php

namespace App\Classes;

use App\Jobs\EnableSharedAccount;
use App\Models\Client;
use App\Models\Transaction;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

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
//                        "MODERATION",
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

        if($this->hasErrors($response))
        {
            return $this->wrapResponse(ResponseAlias::HTTP_SERVICE_UNAVAILABLE, __('Error'), (array)$response->object());
        }

        return count(json_decode($response->body())->result->Campaigns);

    }

    public function getDisabledSharedAccount(array $logins)
    {
        $response = self::storeRequest(
            self::$urlV5 . 'agencyclients',
            [
                'method' => 'get',
                'params' => [
                    'SelectionCriteria' => [
                        'Logins' => $logins,
                        'Archived' => 'NO'
                    ],
                    'FieldNames' => [
                        'Settings',
                        'ClientId',
                    ],
                ]
            ]
        );

        return $response->object();
    }

    public function enableSharedAccount(Client $client): bool
    {
        $response = self::storeRequest(
            self::$urlV4,
            [
                'method' => 'EnableSharedAccount',
                'token' => self::$token,
                'locale' => 'ru',
                'param' => [
                    'Login' => $client->login,
                ],
            ]
        );

        if(!$this->hasErrors($response))
        {
            $client->update(
                [
                    'account_id' => $response->object()->data->AccountID,
                ]
            );

            return true;
        }

        return false;

    }

    public function deposit(array $params, Client $client, Request $request)
    {
        $user = request()->user();
        $amountDeposit = (int)$request->get('amount_deposit');
        $amount = (int)$request->get('amount');

        if(!$this->canDoDeposit($client))
        {
            return $this->wrapResponse(ResponseAlias::HTTP_SERVICE_UNAVAILABLE, __('At the moment you cannot top up your balance. Either you do not have a single active advertising company, or the joint account has not yet been connected (connected automatically). Please try again later.'));
        }

        if(!$this->checkCommission($amountDeposit, $amount))
        {
            return $this->wrapResponse(ResponseAlias::HTTP_SERVICE_UNAVAILABLE, __('Invalid amount'));
        }

        $transaction = $user->transactions()->create(
            [
                'type' => Transaction::TYPE_DEPOSIT_YANDEX_ACCOUNT,
                'status' => Transaction::STATUS_NEW,
                'order_id' => Transaction::generateUUID(),
                'amount_deposit' => $amountDeposit,
                'amount' => $amount,
                'method_type' => 'yandex_invoice'
            ]
        );

//        $response = Http::post(
//            self::$urlV4,
//            [
//                'method' => 'AccountManagement',
//                'finance_token' => $this->generateFinanceToken('AccountManagement', 'Deposit', $transaction->id),
//                'operation_num' => $transaction->id,
//                'token' => self::$token,
//                'locale' => 'ru',
//                'param' => [
//                    'Action' => 'Deposit',
//                    'Payments' => $params,
//                ],
//            ]
//        );

//        dd($response->object());

        return false;

    }

    protected function generateFinanceToken(string $action, string $method, int $operationNum): string
    {
        return hash('sha256', self::$masterToken . $operationNum . $action . $method . self::$login);
    }

    protected static function storeRequest(string $url, array $data, array $headers = []): PromiseInterface|Response
    {
        $baseHeaders = [
            'Accept-Language' => 'ru',
        ];

        $headers = array_merge($baseHeaders, $headers);
        return Http::withHeaders($headers)->withToken(self::$token)->post($url, $data);
    }

    protected function hasErrors(Response $response): bool
    {
        $object = $response->object();

        if(isset($object->data->Errors))
        {
            return true;
        }
        if(isset($object->error))
        {
            return true;
        }

        return false;

    }

    protected function canDoDeposit(Client $client): bool
    {
        if($client->qty_campaigns > 0 && $client->is_enable_shared_account == true)
        {
            return true;
        }

        if($client->qty_campaigns > 0 && $client->account_id == null)
        {
            $this->enableSharedAccount($client);
            return false;
        }

        return false;
    }

    protected function checkCommission(int $amountDeposit, int $amount): bool
    {
        return $amountDeposit + ($amountDeposit * 0.2) == $amount;
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
