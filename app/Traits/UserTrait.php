<?php

namespace App\Traits;

use App\Http\Controllers\v1\Api\ClosingDocumentController;
use App\Http\Requests\v1\UserRequest;
use App\Models\BalanceAccount;
use App\Models\Client;
use App\Models\Contract;
use App\Models\Report;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

trait UserTrait
{
    public function generateClosingDocuments(User $user, Report $report): void
    {
        $data = (array)@json_decode($report->data);

        if(!empty($data))
        {
            $logins = array_keys($data);

            Client::with('contract')->whereIn('login', $logins)->chunk(150, function(Collection $clients) use ($report, $user) {
                if($clients->isNotempty())
                {
                    $clients->each(function (Client $client) use ($report, $user)
                    {
                        $contract = $client->contract;
                        if(
                            $contract !== null &&
                            (
                                $client->contract->contract_type == Contract::LEGAL_ENTITY ||
                                $client->contract->contract_type == Contract::INDIVIDUAL_ENTREPRENEUR
                            )
                        )
                        {
                            $amount = getClientAmountByReport($client->login, $report);
                            $amountNDS = 0;
                            if($amount)
                            {
                                $amountNDS = rubToKop($amount + ($amount * env('NDS_KOEF')));
                                $amount = rubToKop($amount);
                            }

                            $dateGenerated = Carbon::now()->subMonth()->endOfMonth();

                            $closingAct = $contract->closingActs()->create(
                                [
                                    'date_generated' => $dateGenerated,
                                    'amount' => $amount,
                                    'amount_nds' => $amountNDS,
                                ]
                            );

                            $closingInvoice = $contract->closingInvoice()->create(
                                [
                                    'date_generated' => $dateGenerated,
                                    'amount' => $amount,
                                    'amount_nds' => $amountNDS,
                                ]
                            );

                            $closingDocument = $user->closingDocuments()->create(
                                [
                                    'contract_id' => $contract->id,
                                    'closing_act_id' => $closingAct->id,
                                    'closing_invoice_id' => $closingInvoice->id,
                                ]
                            );

                            ClosingDocumentController::generateClosingActPdf($contract, $closingInvoice, $closingAct, $closingDocument);
                            ClosingDocumentController::generateClosingInvoicePdf($contract, $closingInvoice, $closingAct, $closingDocument);

                        }
                    });
                }
            });
        }
    }

    public function accrueCashback(User $user, Report $report): void
    {
        $resultCost = 0;
        $data = (array)@json_decode($report->data);

        if($user->parent_id !== null)
        {
            $user = User::findOrFail($user->parent_id);
        }

        $balanceAccount = $user->balanceAccount(BalanceAccount::BALANCE_REWARD)->lockForUpdate()->firstOrFail();

        if(!empty($data))
        {
            foreach($data as $login => $arrItems)
            {
                $resultCost += getClientAmountByReport($login, $report);
            }

            if($resultCost > 0)
            {
                $amountCashBack = $resultCost * env('YANDEX_CASHBACK_COEFFICIENT');
                $amountCashBack = rubToKop((float)$amountCashBack);
                $balanceAccount->increaseBalance($amountCashBack);

                $user->transactions()->create(
                    [
                        'type' => Transaction::TYPE_DEPOSIT,
                        'status' => Transaction::STATUS_CONFIRMED,
                        'payment_id' => null,
                        'order_id' => Transaction::generateUUID(),
                        'amount_deposit' => $amountCashBack,
                        'amount' => $amountCashBack,
                        'data' => $report->data,
                        'method_type' => Transaction::METHOD_TYPE_CASHBACK,
                        'balance_account_type' => BalanceAccount::BALANCE_REWARD,
                    ]
                );
            }
        }
    }

    public function updateUser(UserRequest $request): bool
    {
        if ($request->user()->update($request->validatedUser())) return true;
        return false;
    }

    public function updatePassword(UserRequest $request): bool
    {
        $data = $request->validated();
        return (bool)$request->user()->update(['password' => Hash::make($data['new_password'])]);
    }

}
