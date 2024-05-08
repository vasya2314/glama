<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentInvoice extends Mailable
{
    use Queueable, SerializesModels;

    protected $paymentInvoice;

    /**
     * Create a new message instance.
     */
    public function __construct($paymentInvoice)
    {
        $this->paymentInvoice = $paymentInvoice;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('Payment Invoice'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $paymentInvoice = $this->paymentInvoice;

        return new Content(
            markdown: 'payment.invoice-text',
            with: [
                'paymentInvoice' => $paymentInvoice,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
