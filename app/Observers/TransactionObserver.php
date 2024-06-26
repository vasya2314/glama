<?php

namespace App\Observers;

use App\Models\Transaction;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "updated" event.
     */
    public function updated(Transaction $transaction): void
    {
        if(
            $transaction->type == Transaction::TYPE_DEPOSIT &&
            $transaction->method_type == Transaction::METHOD_TYPE_INVOICE &&
            $transaction->status = Transaction::STATUS_EXECUTED
        )
        {
            $transaction->paymentClosingInvoice()->create(
                [
                    'contract_id' => $transaction->contract_id,
                    'amount' => $transaction->amount_deposit,
                ]
            );
        }
    }

    /**
     * Handle the Transaction "deleted" event.
     */
    public function deleted(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "restored" event.
     */
    public function restored(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "force deleted" event.
     */
    public function forceDeleted(Transaction $transaction): void
    {
        //
    }
}
