<?php

namespace App\Http\Controllers\v1\Api;

use App\Classes\Tinkoff;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\PaymentRequest;
use App\Jobs\SendPaymentInvoiceToEmail;
use App\Models\Contract;
use App\Models\Operation;
use App\Models\Transaction;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    /**
     * @throws ConnectionException
     */
    public function deposit(PaymentRequest $request): JsonResponse
    {
        $user = $request->user();
        $contract = Contract::findOrFail($request->get('contract_id'));

        if($contract->contractable_type == Contract::NATURAL_PERSON)
        {
            if($operation = $user->operations()->create($request->storeOperation('deposit')))
            {
                return $this->depositForNaturalPerson($operation, $request);
            }
        }

        if(
            $contract->contractable_type == Contract::LEGAL_ENTITY ||
            $contract->contractable_type == Contract::INDIVIDUAL_ENTREPRENEUR
        )
        {
            if($operation = $user->operations()->create($request->storeOperation('deposit_invoice')))
            {
                return $this->generateInvoice($operation, $request, $contract);
            }
        }

        return $this->wrapResponse(Response::HTTP_INTERNAL_SERVER_ERROR, __('Error'));

    }

    public function notify(): void
    {
        try {
            $request = (array)json_decode(file_get_contents('php://input'));

            $request['Password'] = config('tinkoff')['secret_key'];
            ksort($request);
            $original_token = $request['Token'];
            unset($request['Token']);

            $request['Success'] = $request['Success'] === true ? 'true' : 'false';
            $values = implode('', $request);
            $token = hash('sha256', $values);

            if ($token == $original_token)
            {
                $transaction = Transaction::where('payment_id', $request['PaymentId'])->first();

                if($transaction)
                {
                    $transaction->update(
                        [
                            'status' => $request['Status'],
                            'card_id' => $request['CardId'],
                            'pan' => $request['Pan'],
                        ]
                    );

                    if($request['Status'] === 'CONFIRMED')
                    {
                        $user = $transaction->user;
                        $user->changeBalance((int)$request['Amount']);
                    }
                }
                die('OK');
            } else {
                die('TOKEN ISNOT CORRECT'); // указан некорректный пароль
            }
        } catch (\Exception $e) {
            die($e);
        }
    }

    private function depositForNaturalPerson(Operation $operation, PaymentRequest $request): JsonResponse
    {
        $user = $request->user();
        $apiUrl = config('tinkoff')['api_url'];
        $terminal = config('tinkoff')['terminal'];
        $secretKey = config('tinkoff')['secret_key'];

        $tinkoff = new Tinkoff($apiUrl, $terminal, $secretKey);

        $payment = [
            'OrderId' => $operation->id,
            'Amount' => $request->get('amount'),
            'Language' => 'ru',
            'Description' => 'Покупка GCoins',
            'Email' => $user->email,
            'Phone' => $user->phone,
            'Name' => $user->name,
            'Taxation' => 'usn_income_outcome',
        ];

        $items[] = [
            'Name' => 'Покупка GCoins',
            'Price' => $request->get('amount'),
            'Quantity' => 1,
            'NDS' => 'none',
        ];

        $paymentURL = $tinkoff->paymentURL($payment, $items);

        if(!$paymentURL)
        {
            return $this->wrapResponse(Response::HTTP_BAD_REQUEST, __('Bad request'), (array)json_decode($tinkoff->error));
        }

        if($operation->transaction()->create($request->storeNaturalPersonTransaction($tinkoff)))
        {
            return $this->wrapResponse(Response::HTTP_OK, __('Ok'), (array)json_decode($tinkoff->response));
        }

        return $this->wrapResponse(Response::HTTP_INTERNAL_SERVER_ERROR, __('Error'));
    }

    /**
     * @throws ConnectionException
     */
    private function generateInvoice(Operation $operation, PaymentRequest $request, Contract $contract): JsonResponse
    {
        $token = config('tinkoff')['token'];
        $body = $this->generateBody($operation, $contract, $request);
        $response = Http::withToken($token)->post(env('TINKOFF_API') . 'invoice/send', $body);

        if($response->status() !== 200)
        {
            return $this->wrapResponse($response->status(), __('Error'), (array)json_decode($response->body()));
        }

        if($operation->paymentInvoice()->create($request->storePaymentInvoice($response)))
        {
            return $this->wrapResponse(Response::HTTP_OK, __('The invoice has been successfully generated. We have also sent a letter to your email.'), (array)json_decode($response->body()));
        }

        return $this->wrapResponse(Response::HTTP_INTERNAL_SERVER_ERROR, __('Error'));

    }

    private function generateBody(Operation $operation, Contract $contract, Request $request): array
    {
        $contractVariation = $contract->contractable;
        $body = [
            "invoiceNumber" => (string)$operation->id,
            "dueDate" => date('Y-m-d'),
            "invoiceDate" => date('Y-m-d', strtotime('+1 day')),
            "accountNumber" => $contractVariation->correspondent_account,
            "payer" => [
                "name" => $contractVariation->company_name,
                "inn" => $contractVariation->inn,
            ],
            "items" => [
                [
                    "name" => "Покупка GCoins",
                    "price" => $request->get('amount'),
                    "unit" => "Шт",
                    "vat" => "None",
                    "amount" => 1,
                ]
            ],
            "contacts" => [
                [
                    "email" => $contractVariation->email,
                ],
            ],
            "contactPhone" => $contractVariation->phone,
            "comment" => "Покупка GCoins"
        ];

        if($contractVariation == Contract::LEGAL_ENTITY)
        {
            $body['payer']['kpp'] = $contractVariation->kpp;
        }

        return $body;

    }

    private function wrapResponse(int $code, string $message, ?array $resource = []): JsonResponse
    {
        $result = [
            'code' => $code,
            'message' => $message
        ];

        if (count($resource)) $result = array_merge($result, ['resource' => $resource]);

        return response()->json($result);
    }

}
