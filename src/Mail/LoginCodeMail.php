<?php

namespace Acsi\AuthCode\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoginCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public $user,
        public string $code,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Login Code - '.config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'auth-code::emails.login-code',
            with: [
                'user' => $this->user,
                'code' => $this->code,
            ],
        );
    }
}