<?php

namespace App\Console\Commands;

use App\Facades\YandexDirect;
use App\Http\Controllers\v1\Api\ClosingDocumentController;
use App\Models\BalanceAccount;
use App\Models\Client;
use App\Models\ClosingAct;
use App\Models\Contract;
use App\Models\Report;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\UserTrait;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DevelopCommand extends Command
{
    use UserTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'develop';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {

        try {
            DB::beginTransaction();

            $user = User::find(1);
            $report = Report::find(1);

            $this->accrueCashback($user, $report);
            $this->generateClosingDocuments($user, $report);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }

    }
}
