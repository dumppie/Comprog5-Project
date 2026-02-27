<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $verificationUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
        
        // Debug: Check if user exists and has ID
        if (!$user || !$user->id) {
            throw new \Exception('Invalid user provided to EmailVerificationMail');
        }
        
        // Ensure token exists before generating URL
        if (!$user->email_verification_token) {
            $user->email_verification_token = \Str::random(60);
            $user->save();
        }
        
        // Debug: Log the token
        \Log::info('Email verification token generated: ' . $user->email_verification_token);
        
        $this->verificationUrl = route('verification.verify', [
        'token' => $user->email_verification_token
    ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verify Your Email Address - La Petite Pâtisserie',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.verification',
            with: [
                'userName' => $this->user->name,
                'verificationUrl' => $this->verificationUrl,
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
