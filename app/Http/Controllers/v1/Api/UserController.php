<?php

namespace App\Http\Controllers\v1\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\v1\Api\Auth\LoginController;
use App\Http\Controllers\v1\Api\Auth\RegisterController;
use App\Http\Requests\v1\AuthRequest;
use App\Http\Requests\v1\UserRequest;
use App\Http\Resources\v1\UserResource;
use App\Mail\AttachToAgency;
use App\Models\BalanceAccount;
use App\Models\ClosingAct;
use App\Models\Contract;
use App\Models\RewardContract;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\UserTrait;
use ErrorException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    use UserTrait;

    public function confirmAttachToAgency(Request $request, User $user, User $agencyUser): JsonResponse
    {
        if (!$request->hasValidSignature()) {
            return $this->wrapResponse(Response::HTTP_UNAUTHORIZED, __('Error'));
        }

        $user->update(
            [
                'parent_id' => $agencyUser->id,
            ]
        );

        return $this->wrapResponse(Response::HTTP_OK, __('Ok'));

    }

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

    public function getBalance(Request $request, string $balanceType): JsonResponse
    {
        $user = $request->user();
        $balanceAccount = $user->balanceAccount($balanceType)->firstOrFail();

        return $this->wrapResponse(Response::HTTP_OK, __('Ok'), ['balance' => kopToRub((int)$balanceAccount->balance)]);
    }

    public function withdrawalMoneyMain(UserRequest $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->validated();
        $amount = (int)$data['amount'];

        if(!BalanceAccount::isEnoughBalance($amount, $user, BalanceAccount::BALANCE_MAIN))
        {
            return $this->wrapResponse(
                Response::HTTP_SERVICE_UNAVAILABLE,
                __('Not enough balance')
            );
        }

        $contract = Contract::findOrFail($data['contract_id']);
        $contractData = json_decode($contract->data);

        $jsonData = [];
        if($contract->contract_type == RewardContract::NATURAL_PERSON)
        {
            $jsonData['name'] = $contract->dispay_name;
        } else {
            $jsonData['accountNumber'] = $contractData->correspondent_account;
            $jsonData['name'] = $contract->display_name;
        }

        $user->transactions()->create(
            [
                'transactionable_type' => Contract::class,
                'transactionable_id' => $data['contract_id'],
                'type' => Transaction::TYPE_REMOVAL,
                'status' => Transaction::STATUS_NEW,
                'payment_id' => null,
                'order_id' => Transaction::generateUUID(),
                'amount_deposit' => $amount,
                'amount' => $amount,
                'data' => generateTransactionData($jsonData),
                'method_type' => Transaction::METHOD_TYPE_TRANSFER,
                'balance_account_type' => BalanceAccount::BALANCE_MAIN,
            ]
        );

        return $this->wrapResponse(Response::HTTP_OK, __('Ok'));
    }

    public function withdrawalMoneyReward(UserRequest $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->validated();
        $amount = (int)$data['amount'];

        if(!BalanceAccount::isEnoughBalance($amount, $user, BalanceAccount::BALANCE_REWARD))
        {
            return $this->wrapResponse(
                Response::HTTP_SERVICE_UNAVAILABLE,
                __('Not enough balance')
            );
        }

        $rewardContract = RewardContract::findOrFail($data['reward_contract_id']);
        $rewardContractData = json_decode($rewardContract->data);

        $jsonData = [];
        if($rewardContract->contract_type == RewardContract::NATURAL_PERSON)
        {
            $jsonData['pan'] = $rewardContractData->card_number;
            $jsonData['name'] = $rewardContract->display_name;
        } else {
            $jsonData['accountNumber'] = $rewardContractData->correspondent_account;
            $jsonData['name'] = $rewardContract->dispay_name;
        }

        $user->transactions()->create(
            [
                'transactionable_type' => RewardContract::class,
                'transactionable_id' => $data['reward_contract_id'],
                'type' => Transaction::TYPE_REMOVAL,
                'status' => Transaction::STATUS_NEW,
                'payment_id' => null,
                'order_id' => Transaction::generateUUID(),
                'amount_deposit' => $amount,
                'amount' => $amount,
                'data' => generateTransactionData($jsonData),
                'method_type' => Transaction::METHOD_TYPE_TRANSFER,
                'balance_account_type' => BalanceAccount::BALANCE_REWARD,
            ]
        );

        return $this->wrapResponse(Response::HTTP_OK, __('Ok'));
    }

    public function createUser(AuthRequest $request): JsonResponse
    {
        $user = $request->user();

        return (new RegisterController())->register($request, User::TYPE_SIMPLE, $user->id);
    }

    public function attachUser(Request $request, User $user): JsonResponse
    {
        $agencyUser = $request->user();

        if(
            $agencyUser->user_type !== $user::TYPE_AGENCY ||
            $user->user_type !== $user::TYPE_SIMPLE ||
            $user->role !== $user::ROLE_USER
        )
        {
            return $this->wrapResponse(Response::HTTP_BAD_REQUEST, __('Error'));
        }

        if($user->parent_id !== null)
        {
            return $this->wrapResponse(Response::HTTP_BAD_REQUEST, __('The user is already assigned to the agency'));
        }

        dispatch(function () use ($user, $agencyUser) {
            $url = env('VIEW_APP_URL') . '/attach-to-agency/?url=' . URL::signedRoute('confirm-attach-to-agency', [
                'user' => $user->id,
                'agencyUser' => $agencyUser->id,
            ]);
            Mail::to($user->contact_email)->send(new AttachToAgency($user, $agencyUser, $url));
        })->afterResponse();

        return $this->wrapResponse(Response::HTTP_OK, __('You have successfully submitted a request to add a user to your agency'));

    }

    public function allUsers(Request $request): JsonResponse
    {
        $user = $request->user();

        $childUsers = UserResource::collection($user->childUsers()->paginate(12))
            ->response()
            ->getData(true);

        return $this->wrapResponse(Response::HTTP_OK, __('All users'), (array)$childUsers);
    }

    public function agencyLogin(AuthRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = User::findOrFail($data['user_id']);

        return (new LoginController())->login($request, $user);
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
