<?php

namespace App\Http\Controllers\v1\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\v1\Api\Auth\RegisterController;
use App\Http\Requests\v1\AuthRequest;
use App\Http\Requests\v1\TicketRequest;
use App\Http\Resources\v1\TicketResource;
use App\Models\BalanceAccount;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\User;
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

    public function confirmTransaction(Request $request, User $user, Transaction $transaction): JsonResponse
    {
        Gate::authorize('confirmTransaction', [Transaction::class, $user,$transaction]);

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
                    'status' => Transaction::STATUS_CONFIRMED,
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
