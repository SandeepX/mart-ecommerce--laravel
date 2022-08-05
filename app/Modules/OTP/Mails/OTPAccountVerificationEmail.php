<?php

namespace App\Modules\OTP\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OTPAccountVerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = config('mail.from.address');
        $subject = 'Account Verification Email';
        $name = config('mail.from.name');

        return $this->view('OTP::emails.send_account_verification_email_otp')
            ->from($address, $name)
            ->subject($subject)
            ->with(
                [
                    'otp' => $this->data['otp']
                ]
            );
    }

}
