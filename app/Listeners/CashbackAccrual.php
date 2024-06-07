<?php

namespace App\Listeners;

use App\Events\ReportHasBeenGenerated;

class CashbackAccrual
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ReportHasBeenGenerated $event): void
    {
        dump(11111111111);
    }
}
