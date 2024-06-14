<?php

namespace App\Classes;

use App\Models\Client;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

abstract class YandexDirectExtend
{
    protected static string $token;
    protected static string $urlV4;
    protected static string $urlV5;
    protected static string $masterToken;
    protected static string $login;
    protected static string $contractId;

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
        self::$contractId = config('yandex')['contract_id'];
    }

    protected function parseCSV(string $csv): array|string
    {
        $actualData = [];
        $dataRows = str_getcsv($csv,"\n");

        if(!empty($dataRows))
        {
            $headers = str_getcsv($dataRows[0],"\t");
            unset($dataRows[0]);

            if(!empty($dataRows) && is_array($dataRows)) {
                foreach ($dataRows as $row)
                {
                    $parseRow = str_getcsv($row,"\t");
                    $res = array_combine($headers, $parseRow);
                    $actualData[] = $res;
                }
            } else {
                return 'EMPTY';
            }
        }

        return $actualData;
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

        if(
            isset($object->data->Errors) ||
            isset($object->error) ||
            isset($object->error_code)
        )
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

    protected function wrapResponse(int $code, string $message, ?array $resource = []): JsonResponse
    {
        $result = [
            'code' => $code,
            'message' => $message
        ];

        if (count($resource)) $result = array_merge($result, ['resource' => $resource]);

        return response()->json($result, $code);
    }

    protected function getRequestReportParams(): array
    {
        return [
            'params' => [
                'SelectionCriteria' => (object)[],
                'FieldNames' => ['Date', 'CampaignId', 'Cost'],
                'OrderBy' => [
                    [
                        'Field' => 'Date'
                    ]
                ],
                'ReportName' => 'REPORT',
                'ReportType' => 'CAMPAIGN_PERFORMANCE_REPORT',
                'DateRangeType' => 'LAST_MONTH',
                'Format' => 'TSV',
                'IncludeVAT' => 'NO',
                'IncludeDiscount' => 'YES',
            ]
        ];
    }

    protected function getRequestReportHeaders(string $login): array
    {
        return [
            'Client-Login' => $login,
            'processingMode' => 'offline',
            'skipReportHeader' => 'true',
            'skipReportSummary' => 'true',
        ];
    }

    abstract function enableSharedAccount(Client $client);
}
