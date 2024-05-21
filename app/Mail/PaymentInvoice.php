<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Symfony\Component\ErrorHandler\Debug;

class PaymentInvoice extends Mailable
{
    use Queueable, SerializesModels;

    protected $transaction;

    /**
     * Create a new message instance.
     */
    public function __construct($transaction)
    {
        $this->transaction = $transaction;
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
        $pdfUrl = json_decode($this->transaction->data)->pdfUrl;

        return new Content(
            markdown: 'payment.invoice-text',
            with: [
                'pdfUrl' => $pdfUrl,
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
