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
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DevelopCommand extends Command
{
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

            // $amount = $user->accrueCashback($report);

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
                                if($amount) $amount = rubToKop($amount);

                                $closingAct = $contract->closingActs()->create(
                                    [
                                        'date_generated' => date('Y-m-d H:i:s'),
                                        'amount' => $amount,
                                    ]
                                );

                                $closingInvoice = $contract->closingInvoice()->create(
                                    [
                                        'date_generated' => date('Y-m-d H:i:s'),
                                        'amount' => $amount,
                                    ]
                                );

                                $user->closingDocuments()->create(
                                    [
                                        'contract_id' => $contract->id,
                                        'closing_act_id' => $closingAct->id,
                                        'closing_invoice_id' => $closingInvoice->id,
                                    ]
                                );
                            }
                        });
                    }
                });

            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }

    }
}
