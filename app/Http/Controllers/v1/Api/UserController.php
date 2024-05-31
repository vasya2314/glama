<?php

namespace App\Http\Controllers\v1\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\UserRequest;
use App\Http\Resources\v1\UserResource;
use App\Models\BalanceAccount;
use App\Models\Message;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\UserTrait;
use ErrorException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    use UserTrait;

    /**
     * @throws ErrorException
     */
    public function update(UserRequest $request): JsonResponse
    {
        if ($this->updateUser($request))
        {
            $user = (new UserResource($request->user()))
                ->response()
                ->getData(true);

            return $this->wrapResponse(Response::HTTP_OK, __('User updated successfully.'), $user);

        }

        throw new ErrorException(__('Failed to get service, please try again.'), Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @throws ErrorException
     */
    public function changePassword(UserRequest $request): JsonResponse
    {
        if ($this->updatePassword($request))
        {
            return $this->wrapResponse(Response::HTTP_OK, __('The password updated successfully.'));
        }

        throw new ErrorException(__('Failed to get service, please try again.'), Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function detail(Request $request): JsonResponse
    {
        $user = (new UserResource($request->user()))
            ->response()
            ->getData(true);

        return $this->wrapResponse(Response::HTTP_OK, __('Your profile'), $user);
    }

    public function getBalance(Request $request): JsonResponse
    {
        $user = $request->user();
        $balanceAccount = $user->balanceAccount;

        return $this->wrapResponse(Response::HTTP_OK, __('Ok'), ['balance' => kopToRub((int)$balanceAccount->balance)]);
    }

    public function  withdrawalMoney(UserRequest $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->validated();
        $amount = (int)$data['amount'];

        if(!BalanceAccount::isEnoughBalance($amount, $user))
        {
            return $this->wrapResponse(
                ResponseAlias::HTTP_SERVICE_UNAVAILABLE,
                __('Not enough balance')
            );
        }

        $user->transactions()->create(
            [
                'type' => Transaction::TYPE_REMOVAL,
                'status' => Transaction::STATUS_NEW,
                'payment_id' => null,
                'order_id' => Transaction::generateUUID(),
                'amount_deposit' => $amount,
                'amount' => $amount,
                'data' => null,
                'method_type' => Transaction::METHOD_TYPE_CARD,
            ]
        );

        return $this->wrapResponse(Response::HTTP_OK, __('Ok'));
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

        if(!BalanceAccount::isEnoughBalance($amount, $user))
        {
            return $this->wrapResponse(
                Response::HTTP_SERVICE_UNAVAILABLE,
                __('Not enough balance')
            );
        }

        try {
            DB::beginTransaction();

            $balanceAccount = $transaction->user->balanceAccount()->lockForUpdate()->first();
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

        if(!BalanceAccount::isEnoughBalance($amount, $user))
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
