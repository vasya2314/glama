<?php

namespace App\Http\Controllers\v1\Api;

use App\Facades\YandexDirect;
use App\Http\Controllers\Controller;
use App\Http\Controllers\v1\Api\Auth\RegisterController;
use App\Http\Requests\v1\AuthRequest;
use App\Http\Requests\v1\TicketRequest;
use App\Http\Requests\v1\YandexDirectPaymentRequest;
use App\Http\Resources\v1\TicketResource;
use App\Models\BalanceAccount;
use App\Models\Contract;
use App\Models\PaymentClosingInvoice;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    public function createAgencyUser(AuthRequest $request): JsonResponse
    {
        return (new RegisterController())->register($request, User::TYPE_AGENCY);
    }

    /**
     * @throws \ErrorException
     */
    public function assignedTo(TicketRequest $request, Ticket $ticket): JsonResponse
    {
        if ($ticket->update($request->validated())) {
            $ticket = (new TicketResource($ticket))
                ->response()
                ->getData(true);

            return $this->wrapResponse(Response::HTTP_OK, __('The ticket updated successfully.'), $ticket);
        }

        throw new \ErrorException(__('Failed to get service, please try again.'), Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function executeTransaction(Request $request, User $user, Transaction $transaction): JsonResponse
    {
        Gate::authorize('executeTransaction', [Transaction::class, $user,$transaction]);

        $amount = (int)$transaction->amount;

        if(
            $transaction->status !== Transaction::STATUS_NEW &&
            $transaction->status !== Transaction::STATUS_SUBMITTED
        ) {
            return $this->wrapResponse(Response::HTTP_SERVICE_UNAVAILABLE, __('The transaction has an invalid status'));
        }

        if(!BalanceAccount::isEnoughBalance($amount, $user, $transaction->balance_account_type))
        {
            return $this->wrapResponse(
                Response::HTTP_SERVICE_UNAVAILABLE,
                __('Not enough balance')
            );
        }

        try {
            DB::beginTransaction();

            $balanceAccount = $transaction->user->balanceAccount($transaction->balance_account_type)->lockForUpdate()->first();
            if($balanceAccount)
            {
                $balanceAccount->decreaseBalance($amount);
            }

            $transaction->update(
                [
                    'status' => Transaction::STATUS_EXECUTED,
                ]
            );

            DB::commit();
            return $this->wrapResponse(Response::HTTP_OK, __('Ok'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }

        return $this->wrapResponse(Response::HTTP_INTERNAL_SERVER_ERROR, __('Error'));

    }

    public function rejectTransaction(Request $request, User $user, Transaction $transaction): JsonResponse
    {
        Gate::authorize('rejectTransaction', [Transaction::class, $user,$transaction]);

        $amount = (int)$transaction->amount;

        if(
            $transaction->status !== Transaction::STATUS_NEW &&
            $transaction->status !== Transaction::STATUS_SUBMITTED
        ) {
            return $this->wrapResponse(Response::HTTP_SERVICE_UNAVAILABLE, __('The transaction has an invalid status'));
        }

        if(!BalanceAccount::isEnoughBalance($amount, $user, $transaction->balance_account_type))
        {
            return $this->wrapResponse(
                Response::HTTP_SERVICE_UNAVAILABLE,
                __('Not enough balance')
            );
        }

        try {
            DB::beginTransaction();

            $transaction->update(
                [
                    'status' => Transaction::STATUS_DRAFT,
                ]
            );

            DB::commit();
            return $this->wrapResponse(Response::HTTP_OK, __('Ok'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }

        return $this->wrapResponse(Response::HTTP_INTERNAL_SERVER_ERROR, __('Error'));

    }

    public function returnMoneyYandexDirect(YandexDirectPaymentRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $contract = Contract::findOrFail($data['contract_id']);
            $user = $contract->user;
            $amount = $data['amount'];

            if(
                $contract->contract_type == Contract::INDIVIDUAL_ENTREPRENEUR ||
                $contract->contract_type == Contract::LEGAL_ENTITY
            ) {
                $paymentClosingInvoices = PaymentClosingInvoice::where('contract_id', $contract->id)->where('amount', '>', 0)
                    ->orderBy('created_at', 'ASC')
                    ->get();

                ClosingDocumentController::getAndModifyNeedTransactions($paymentClosingInvoices, $data['amount']);
            }

            $balanceAccount = $user->balanceAccount(BalanceAccount::BALANCE_MAIN)->lockForUpdate()->firstOrFail();
            $balanceAccount->increaseBalance($data['amount']);

            $user->transactions()->create(
                [
                    'type' => Transaction::TYPE_DEPOSIT,
                    'status' => Transaction::STATUS_EXECUTED,
                    'payment_id' => null,
                    'order_id' => Transaction::generateUUID(),
                    'amount_deposit' => $amount,
                    'amount' => $amount,
                    'data' => null,
                    'method_type' => Transaction::METHOD_TYPE_RETURN,
                    'balance_account_type' => BalanceAccount::BALANCE_MAIN,
                ]
            );

            return $this->wrapResponse(Response::HTTP_OK, __('Ok'));

        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return $this->wrapResponse(Response::HTTP_OK, __('Error'));

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
