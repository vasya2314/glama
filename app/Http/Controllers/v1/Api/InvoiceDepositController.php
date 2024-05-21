<?php

namespace App\Http\Controllers\v1\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\DepositRequest;
use App\Jobs\SendPaymentInvoiceToEmail;
use App\Models\Contract;
use App\Models\Transaction;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class InvoiceDepositController extends Controller
{
    protected ?Contract $contract;
    protected ?Transaction $transaction;

    /**
     * @throws ConnectionException
     */
    public function deposit(DepositRequest $request): JsonResponse
    {
        $this->contract = Contract::findOrFail($request->get('contract_id'));

        if(
            $this->contract->contractable_type == Contract::LEGAL_ENTITY ||
            $this->contract->contractable_type == Contract::INDIVIDUAL_ENTREPRENEUR
        )
        {
            return $this->generateInvoice($request);
        }

        return $this->wrapResponse(Response::HTTP_INTERNAL_SERVER_ERROR, __('Error'));

    }

    /**
     * @throws ConnectionException
     */
    private function generateInvoice(DepositRequest $request): JsonResponse
    {
        $user = $request->user();
        $token = config('tinkoff')['token'];
        $body = $this->generateBody($request);
        $response = Http::withToken($token)->post(env('TINKOFF_API') . 'invoice/send', $body);

        if($response->status() !== 200)
        {
            return $this->wrapResponse($response->status(), __('Error'), (array)json_decode($response->body()));
        }

        if($this->transaction->update($request->updateInvoiceTransaction($response->object(), Transaction::STATUS_SUBMITTED)))
        {
            dispatch(new SendPaymentInvoiceToEmail($this->transaction, $user));
            return $this->wrapResponse(Response::HTTP_OK, __('The invoice has been successfully generated. We have also sent a letter to your email.'), (array)json_decode($response->body()));
        }

        return $this->wrapResponse(Response::HTTP_INTERNAL_SERVER_ERROR, __('Error'));

    }

    private function generateBody(DepositRequest $request): false|array
    {
        $this->transaction = $request->user()->transactions()->create($request->storeInvoiceTransaction());

        if($this->transaction)
        {
            $contractVariation = $this->contract->contractable;

            $body = [
                "invoiceNumber" => (string)$this->transaction->id,
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

        return false;

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
