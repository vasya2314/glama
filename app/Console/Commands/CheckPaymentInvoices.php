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
        $paymentInvoices = PaymentInvoice::where('status', PaymentInvoice::STATUS_SUBMITTED)->get();

        if($paymentInvoices->isNotEmpty())
        {
            $paymentInvoices = $paymentInvoices->chunk(20);

            $paymentInvoices->each(function ($paymentItems) use ($token) {
                $paymentItems->each(function ($item) use ($token) {
                    $response = Http::withToken($token)->withUrlParameters(
                        [
                            'endpoint' => env('TINKOFF_API'),
                            'openapi' => 'openapi',
                            'invoice' => 'invoice',
                            'invoice_id' => $item->invoice_id,
                            'info' => 'info'
                        ]
                    )->get('{+endpoint}/{openapi}/{invoice}/{invoice_id}/{info}');

                    try {
                        DB::beginTransaction();

                        if($response->status() == 200)
                        {
                            $body = json_decode($response->body());

                            if($body->status !== $item->status)
                            {
                                $item->status = $body->status;
                                $item->save();

                                return;
                            }

                            if($body->status == PaymentInvoice::STATUS_EXECUTED)
                            {
                                // ОБНОВИТЬ ЕЩЕ PAYMENT_INVOICE
                                $item->status = $body->status;
                                $item->save();

                                $transaction = Transaction::where('order_id', $item->invoice_id)->first();

                                $transaction->update(
                                    [
                                        'status' => 'CONFIRMED'
                                    ]
                                );

                                $balanceAccount = $transaction->user->balanceAccount;
                                $balanceAccount->increaseBalance((int)$transaction->amount_base);
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
