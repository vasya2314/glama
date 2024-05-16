<?php

namespace App\Classes;

use App\Models\Client;
use Illuminate\Support\Facades\Http;

class YandexDirect
{
    protected string $token;
    protected string $urlV4;
    protected string $urlV5;
    protected string $masterToken;


    public function __construct()
    {
        $this->setProperties();
    }

    public function createInvoice(array $param, Client $client)
    {
        $response = Http::withToken($this->token)->post($this->urlV4, [
            'method' => 'AccountManagement',
            'finance_token' => $this->generateFinanceToken($client),
            'operation_num' => 1,
            'param' => $param,
        ]);

        dd(json_decode($response->body()));

//        return $response->body();
    }

    private function generateFinanceToken(Client $client): string
    {
        $masterToken = $this->masterToken;
        $operationNum = 1;
        $action = 'AccountManagement';
        $usedMethod = 'Invoice';
        $login = $client->login;

//        dd($masterToken, $operationNum, $action, $usedMethod, $login);

        $values = $masterToken . $operationNum . $action . $usedMethod . $login;

        return hash('sha256', $values);
    }

    private function setProperties(): void
    {
        $this->token = config('yandex')['token'];
        $this->urlV4 = env('YANDEX_API_LIVE_V4');
        $this->urlV5 = env('YANDEX_API');
        $this->masterToken = config('yandex')['master_token'];
    }

}
