<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $activation_code;

    public function __construct($activation_code)
    {
        $this->activation_code = $activation_code;
    }

    public function build()
    {
        return $this->subject(__('application.verification_code_subject'))
            ->view('emails.verification_code')
            ->with([
                'activation_code' => $this->activation_code,
            ]);
    }
}
