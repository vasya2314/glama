<?php

namespace App\Console\Commands;

use App\Models\PaymentInvoice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

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

            $paymentInvoices->each(function ($paymentItems, $key) use ($token) {
                $paymentItems->each(function ($item, $key) use ($token) {
                    $response = Http::withToken($token)->withUrlParameters(
                        [
                            'endpoint' => env('TINKOFF_API'),
                            'openapi' => 'openapi',
                            'invoice' => 'invoice',
                            'invoice_id' => $item->invoice_id,
                            'info' => 'info'
                        ]
                    )->get('{+endpoint}/{openapi}/{invoice}/{invoice_id}/{info}');

                    if($response->status() == 200)
                    {
                        $body = json_decode($response->body());
                        if($body->status == PaymentInvoice::STATUS_EXECUTED)
                        {
                            $item->status = $body->status;
                            $item->save();

                            $user = $item->user;
                            $operation = $item->operation;

                            $user->changeBalance((int)$operation->amount);
                        }

                        if($body->status == PaymentInvoice::STATUS_DRAFT)
                        {
                            $item->status = $body->status;
                            $item->save();
                        }
                    }
                });
                dump($paymentItems);
                sleep(1);
            });
        }
    }
}
