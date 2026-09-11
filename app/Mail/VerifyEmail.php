<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $token;
    public $email;

    public function __construct($email, $token) {
        $this->email = $email;
        $this->token = $token;
    }

    public function build() {
        return $this->subject('Verify Your Email')
            ->view('emails.verify')
            ->with(['token' => $this->token, 'email' => $this->email]);
    }
}
