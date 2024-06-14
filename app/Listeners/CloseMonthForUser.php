<?php

namespace App\Listeners;

use App\Events\ReportHasBeenGenerated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CloseMonthForUser
{
    /**
     * Handle the event.
     */
    public function handle(ReportHasBeenGenerated $event): void
    {
        try {
            DB::beginTransaction();

            $user = $event->user;
            $report = $event->report;

            $amountCashback = $user->accrueCashback($report);


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }
}
