<?php

namespace App\Observers;

use App\Models\BalanceAccount;
use App\Models\ClosingAct;
use App\Models\User;
use Carbon\Carbon;

class ClosingActObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(ClosingAct $closingAct): void
    {
        $date =  Carbon::parse($closingAct->date_generated)->format('Y/m/d');

        $closingAct->update(['act_number' => $date . '-' . $closingAct->id]);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(ClosingAct $closingAct): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(ClosingAct $closingAct): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(ClosingAct $closingAct): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(ClosingAct $closingAct): void
    {
        //
    }
}
