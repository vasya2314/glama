<?php

namespace App\Classes;

use App\Models\Client;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class YandexDirect
{
    protected string $token;
    protected string $urlV4;
    protected string $urlV5;
    protected string $masterToken;
    protected string $login;

    public function __construct()
    {
        $this->setProperties();
    }

    public function getAllClients(array $params): ?object
    {
        $response = $this->storeRequest(
            $this->urlV5 . 'agencyclients',
            [
                'method' => 'get',
                'params' => $params,
            ]
        );

        return $response->object();
    }

    public function storeClient(array $params): ?object
    {
        $response = $this->storeRequest(
            $this->urlV5 . 'agencyclients',
            [
                'method' => 'add',
                'params' => $params,
            ]
        );

//        if(!$this->hasErrors($response->object()))
//        {
//            $this->enableSharedAccount($response->object()->result->Login);
//        }

        return $response->object();
    }

    public function enableSharedAccount(string $login)
    {
        $response = $this->storeRequest(
            $this->urlV4,
            [
                'method' => 'EnableSharedAccount',
                'token' => $this->token,
                'param' => [
                    'Login' => $login
                ],
            ]
        );

        dd($response->object());
    }

    public function clientHasActiveCampaigns()
    {

    }

    public function updateCampaignsQty()
    {

    }

    private function storeRequest(string $url, array $data): PromiseInterface|Response
    {
        return Http::withHeaders(['Accept-Language' => 'ru'])->withToken($this->token)->post($url, $data);
    }

//    public function createInvoice(array $param)
//    {
//        $response = Http::post($this->urlV4, [
//            'method' => 'AccountManagement',
////            'finance_token' => $this->generateFinanceToken($client),
//            'finance_token' => $this->generateFinanceToken(),
//            'operation_num' => 1,
//            'token' => $this->token,
//            'param' => $param,
//        ]);
//
//        dd(json_decode($response->body()));
//
////        return $response->body();
//    }
//
//    private function generateFinanceToken(): string
//    {
//        $master_token = $this->masterToken;
//        $operation_num = 1;
//        $action = 'AccountManagement';
//        $used_method = 'Invoice';
//        $login = $this->login;
//
//        return hash("sha256", $master_token . $operation_num . $action . $used_method . $login);
//    }

    private function hasErrors(object $object): bool
    {
        return isset($object->error);
    }

    private function setProperties(): void
    {
        $this->masterToken = config('yandex')['master_token'];
        $this->login = config('yandex')['login'];
        $this->token = config('yandex')['token'];
        $this->urlV4 = env('YANDEX_API_LIVE_V4');
        $this->urlV5 = env('YANDEX_API');
    }

}
