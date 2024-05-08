<?php

namespace App\Jobs;

use App\Mail\PaymentInvoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPaymentInvoiceToEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $paymentInvoice;
    protected $user;

    /**
     * Create a new job instance.
     */
    public function __construct($paymentInvoice, $user)
    {
        $this->paymentInvoice = $paymentInvoice;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user->email)->send(new PaymentInvoice($this->paymentInvoice));
    }
}
