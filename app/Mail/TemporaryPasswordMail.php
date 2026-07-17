<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TemporaryPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $accounts;

    /**
     * @param array $accounts — Array of ['name', 'email', 'password']
     */
    public function __construct(array $accounts)
    {
        $this->accounts = $accounts;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pemulihan Password Inventaris Kemenhan',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.temporary-password',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
