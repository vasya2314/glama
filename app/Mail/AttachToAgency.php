<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Symfony\Component\ErrorHandler\Debug;

class AttachToAgency extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $agencyUser;
    protected $url;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $agencyUser, $url)
    {
        $this->user = $user;
        $this->agencyUser = $agencyUser;
        $this->url = $url;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('Attach to agency'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'agency.attach-to-agency',
            with: [
                'email' => $this->agencyUser->email,
                'url' => $this->url,
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
