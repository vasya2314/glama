<?php

namespace App\Console\Commands;

use App\Models\PaymentInvoice;
use App\Models\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckPaymentInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-payment-invoices';

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
        $token = config('tinkoff')['token'];
        $transactions = Transaction::where('status', Transaction::STATUS_SUBMITTED)->get();

        if($transactions->isNotEmpty())
        {
            $transactions = $transactions->chunk(20);

            $transactions->each(function ($transactionItems) use ($token) {
                $transactionItems->each(function ($transaction) use ($token) {
                    $response = Http::withToken($token)->withUrlParameters(
                        [
                            'endpoint' => env('TINKOFF_API'),
                            'openapi' => 'openapi',
                            'invoice' => 'invoice',
                            'invoice_id' => $transaction->order_id,
                            'info' => 'info'
                        ]
                    )->get('{+endpoint}/{openapi}/{invoice}/{invoice_id}/{info}');

                    try {
                        DB::beginTransaction();

                        if($response->status() == 200)
                        {
                            $body = json_decode($response->body());

                            if($body->status !== $transaction->status)
                            {
                                $transaction->status = $body->status;
                                $transaction->save();
                            }

                            if($body->status == Transaction::STATUS_EXECUTED)
                            {
                                $transaction->update(
                                    [
                                        'status' => Transaction::STATUS_CONFIRMED
                                    ]
                                );

                                $balanceAccount = $transaction->user->balanceAccount()->lockForUpdate()->first();
                                if($balanceAccount)
                                {
                                    $balanceAccount->increaseBalance((int)$transaction->amount_deposit);
                                }
                            }
                        }

                        DB::commit();
                    } catch (\Exception $exception) {
                        DB::rollBack();
                        Log::error($exception);
                    }
                });
                sleep(1);
            });
        }
    }
}
