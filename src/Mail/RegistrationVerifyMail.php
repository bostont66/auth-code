<?php

namespace Acsi\AuthCode\Mail;

use Acsi\AuthCode\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationVerifyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Registration $registration,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verify Your Email - '.config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'auth-code::emails.registration-verify',
            with: [
                'registration' => $this->registration,
                'verifyUrl' => route('auth.register.verify.token', [
                    'email' => $this->registration->email,
                    'token' => $this->registration->token,
                ]),
            ],
        );
    }
}