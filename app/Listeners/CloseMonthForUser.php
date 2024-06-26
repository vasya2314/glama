<?php

namespace App\Listeners;

use App\Events\ReportHasBeenGenerated;
use App\Traits\UserTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CloseMonthForUser
{
    use UserTrait;

    /**
     * Handle the event.
     */
    public function handle(ReportHasBeenGenerated $event): void
    {
        try {
            DB::beginTransaction();

            $user = $event->user;
            $report = $event->report;

            $this->accrueCashback($user, $report);
            $this->generateClosingDocuments($user, $report);

            dump('ВСЕ ОК!');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }
}
