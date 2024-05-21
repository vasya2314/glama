<?php

namespace App\Console\Commands;

use App\Classes\Tinkoff;
use App\Models\BalanceAccount;
use App\Models\PaymentInvoice;
use App\Models\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckPaymentQrState extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-payment-qr';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks the status of the issued payment';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $apiUrl = config('tinkoff')['api_url'];
        $terminal = config('tinkoff')['terminal'];
        $secretKey = config('tinkoff')['secret_key'];

        $transactions = Transaction::where('method_type', 'qr')->whereIn('status', ['NEW', 'PREAUTHORIZING', 'FORM_SHOWED'])->get();
        $tinkoff = new Tinkoff($apiUrl, $terminal, $secretKey);

        if($transactions->isNotEmpty())
        {
            $transactions->each(function ($transaction) use ($tinkoff) {
                try {
                    DB::beginTransaction();

                    $state = $tinkoff->getState($transaction->payment_id);

                    if($transaction->status !== $state)
                    {
                        $transaction->update(
                            [
                                'status' => $state,
                            ]
                        );
                    }

                    if($state == Transaction::STATUS_CONFIRMED)
                    {
                        $balanceAccount = BalanceAccount::lockForUpdate()->where('user_id', $transaction->user_id)->first();
                        if($balanceAccount)
                        {
                            $balanceAccount->increaseBalance((int)$transaction->amount_deposit);
                        }
                    }

                    DB::commit();
                } catch (\Exception $exception) {
                    DB::rollBack();
                    Log::error($exception);
                }
            });
        }
    }
}
