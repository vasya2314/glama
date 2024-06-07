<?php

namespace App\Classes;

use App\Events\ReportHasBeenGenerated;
use App\Jobs\GenerateReportYandexDirect;
use App\Models\BalanceAccount;
use App\Models\Client;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class YandexDirect extends YandexDirectExtend
{
    public function __construct()
    {
        parent::__construct();
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
            return $this->wrapResponse(
                ResponseAlias::HTTP_SERVICE_UNAVAILABLE,
                __('Error'),
                (array)$response->object());
        }

        return count($response->object()->result->Campaigns);

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

    public function deposit(Client $client, Request $request): JsonResponse
    {
        $user = request()->user();
        $amountDeposit = (int)$request->get('amount_deposit');
        $amount = (int)$request->get('amount');

        if(!BalanceAccount::isEnoughBalance($amount, $user))
        {
            return $this->wrapResponse(
                ResponseAlias::HTTP_SERVICE_UNAVAILABLE,
                __('Not enough balance')
            );
        }

        if(!$this->canDoDeposit($client))
        {
            return $this->wrapResponse(
                ResponseAlias::HTTP_SERVICE_UNAVAILABLE,
                __('At the moment you cannot top up your balance. Either you do not have a single active advertising company, or the joint account has not yet been connected (connected automatically). Please try again later.')
            );
        }

        if(!$this->checkCommission($amountDeposit, $amount))
        {
            return $this->wrapResponse(
                ResponseAlias::HTTP_SERVICE_UNAVAILABLE,
                __('Invalid amount')
            );
        }

        if(!$client->is_enable_shared_account)
        {
            return $this->wrapResponse(
                ResponseAlias::HTTP_SERVICE_UNAVAILABLE,
                __('The shared account is not yet connected')
            );
        }

        try {
            DB::beginTransaction();

            $client->lockForUpdate();

            $transaction = $user->transactions()->create(
                [
                    'type' => Transaction::TYPE_DEPOSIT_YANDEX_ACCOUNT,
                    'status' => Transaction::STATUS_NEW,
                    'order_id' => Transaction::generateUUID(),
                    'amount_deposit' => $amountDeposit,
                    'amount' => $amount,
                    'method_type' => Transaction::METHOD_TYPE_YANDEX_INVOICE
                ]
            );

            $response = Http::post(
                self::$urlV4,
                [
                    'method' => 'AccountManagement',
                    'finance_token' => $this->generateFinanceToken('AccountManagement', 'Deposit', $transaction->id),
                    'operation_num' => $transaction->id,
                    'token' => self::$token,
                    'locale' => 'ru',
                    'param' => [
                        'Action' => 'Deposit',
                        'Payments' => [
                            [
                                'AccountID' => $client->account_id,
                                'Amount' => kopToRub($amount),
                                'Currency' => 'RUB',
                                'Contract' => self::$contractId,
                            ]
                        ],
                    ],
                ]
            );

            if($this->hasErrors($response))
            {
                return $this->wrapResponse(
                    ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
                    __('Error'),
                    (array)$response->object(),
                );
            }

            $balanceAccount = $transaction->user->balanceAccount()->lockForUpdate()->firstOrFail();
            if($balanceAccount)
            {
                $balanceAccount->decreaseBalance($amount);
            }

            $client->increaseBalance($amount);

            $transaction->update(
                [
                    'status' => Transaction::STATUS_CONFIRMED,
                ]
            );

            DB::commit();

            return $this->wrapResponse(
                ResponseAlias::HTTP_OK,
                __('The balance has been successfully replenished')
            );

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }

        return $this->wrapResponse(
            ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
            __('Error')
        );
    }

    public function generateReport(User $user): void
    {
        try {
            DB::beginTransaction();

            $resultData = [];
            $clients = $user->clients;

            $report = $user->reports()->create(
                [
                    'data' => json_encode([]),
                ]
            );

            if($clients->isNotEmpty())
            {
                $clients->each(function (Client $client) use ($user, &$resultData, &$report) {

                    $response = self::storeRequest(
                        self::$urlV5 . 'reports',
                        [
                            'params' => [
                                'SelectionCriteria' => (object)[],
                                'FieldNames' => ['Date', 'CampaignId', 'Cost'],
                                'OrderBy' => [
                                    [
                                        'Field' => 'Date'
                                    ]
                                ],
                                'ReportName' => '',
                                'ReportType' => 'CAMPAIGN_PERFORMANCE_REPORT',
                                'DateRangeType' => 'LAST_MONTH',
                                'Format' => 'TSV',
                                'IncludeVAT' => 'YES',
                            ]
                        ],
                        [
                            'Client-Login' => $client->login,
                            'processingMode' => 'offline',
                            'returnMoneyInMicros' => 'false',
                            'skipReportHeader' => 'true',
                            'skipReportSummary' => 'true',
                        ]
                    );

                    if($this->hasErrors($response))
                    {
                        throw new Exception($response->object());
                    }

                    if($response->status() == 200)
                    {
                        $data = $this->parseCSV($response->body());

                        $resultData[$client->login] = $data;
                    }

                    if(
                        $response->status() == 201 ||
                        $response->status() == 202 ||
                        $response->status() == 400 ||
                        $response->status() == 500
                    ) {
                        $resultData[$client->login] = null;
                        dispatch(new GenerateReportYandexDirect($user, $report))->delay(now()->addMinutes(1));
                        Log::info('Отчет для клиента не сформировался и поставлен в очередь | ' . $client . '|' . $response->body());
                    }
                });

                $report->update(
                    [
                        'data' => json_encode($resultData),
                    ]
                );
            } else {
                $resultData = ['NO CLIENTS'];
            }

            if(!in_array(null, $resultData))
            {
                event(new ReportHasBeenGenerated($user, $report));
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }

    }

}
