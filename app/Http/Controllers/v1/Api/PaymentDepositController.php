<?php

namespace App\Http\Controllers\v1\Api;

use App\Classes\Tinkoff;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\DepositRequest;
use App\Models\Contract;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PaymentDepositController extends Controller
{
    protected ?Contract $contract;

    /**
     * @throws ConnectionException
     */
    public function deposit(DepositRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->contract = Contract::findOrFail($data['contract_id']);

        if($this->contract->contract_type == Contract::NATURAL_PERSON)
        {
            return $this->depositForNaturalPerson($request);
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
                $transaction = Transaction::where('payment_id', $request['PaymentId'])->firstOrFail();

                if($transaction)
                {
                    $data = [];
                    if(isset($request['Pan'])) $data['pan'] = $request['Pan'];

                    Log::info(print_r($request, true));

                    if($transaction->status !== $request['Status'])
                    {
                        $transaction->update(
                            [
                                'status' => $request['Status'],
                                'data' => generateTransactionData($data),
                            ]
                        );
                    }

                    if($request['Status'] === Transaction::STATUS_CONFIRMED)
                    {
                        $balanceAccount = $transaction->user->balanceAccount($transaction->balance_account_type)->lockForUpdate()->first();
                        $balanceAccount->increaseBalance((int)$transaction->amount_deposit);
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

    private function depositForNaturalPerson(DepositRequest $request): bool|JsonResponse
    {
        $amountDeposit = $request->get('amount_deposit');
        $amount = $request->get('amount');

        if(!$this->checkCommission((int)$amountDeposit, (int)$amount))
        {
            return $this->wrapResponse(Response::HTTP_BAD_REQUEST, __('Invalid amount'));
        }

        $user = $request->user();
        $apiUrl = config('tinkoff')['api_url'];
        $terminal = config('tinkoff')['terminal'];
        $secretKey = config('tinkoff')['secret_key'];

        $tinkoff = new Tinkoff($apiUrl, $terminal, $secretKey);

        $payment = [
            'OrderId' => Transaction::generateUUID(),
            'Amount' => $amount,
            'Language' => 'ru',
            'Description' => 'Покупка GCoins',
            'Email' => $user->email,
            'Phone' => $user->phone,
            'Name' => $user->name,
            'Taxation' => 'usn_income_outcome',
            'RedirectDueDate' => $this->setDueDate(),
        ];

        $items[] = [
            'Name' => 'Покупка GCoins',
            'Price' => $amount,
            'Quantity' => 1,
            'NDS' => 'none',
        ];

        $paymentURL = $tinkoff->paymentURL($payment, $items);

        if(!$paymentURL) return $this->wrapResponse(Response::HTTP_BAD_REQUEST, __('Bad request'), (array)json_decode($tinkoff->error));

        if(!$user->transactions()->create($request->storeNaturalPersonTransaction(Transaction::TYPE_DEPOSIT, $request, $tinkoff)))
        {
            return $this->wrapResponse(Response::HTTP_INTERNAL_SERVER_ERROR, __('Error'));
        }

        $response = json_decode($tinkoff->response);

        if($request->get('method_type') == Transaction::METHOD_TYPE_CARD)
        {
            return $this->wrapResponse(Response::HTTP_OK, __('Ok'), (array)$response);
        }

        if($request->get('method_type') == Transaction::METHOD_TYPE_QR)
        {
            $tinkoff->generateQr($response);
            $response = json_decode($tinkoff->response);

            return $this->wrapResponse(Response::HTTP_OK, __('Ok'), (array)$response);
        }

        return $this->wrapResponse(Response::HTTP_INTERNAL_SERVER_ERROR, __('Error'));
    }

    private function checkCommission(int $amountDeposit, int $amount): bool
    {
        if(request()->get('method_type') == Transaction::METHOD_TYPE_CARD)
        {
            return $amountDeposit + ($amountDeposit * 0.0259) == $amount;
        }

        if(request()->get('method_type') == Transaction::METHOD_TYPE_QR)
        {
            return $amountDeposit + ($amountDeposit * 0.017) == $amount;
        }

        return false;

    }

    private function setDueDate(): string
    {
        $currentTime = Carbon::now();
        $newTime = $currentTime->addHour();

        return $newTime->format('Y-m-d\TH:i:sP');
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
