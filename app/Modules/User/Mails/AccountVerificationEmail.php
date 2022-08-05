<?php

namespace App\Modules\User\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountVerificationEmail extends Mailable
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

        return $this->view('User::emails.account_verification_email')
            ->from($address, $name)
            ->subject($subject)
            ->with(
                [
                    'name' => $this->data['name'],
                    'userType' => $this->data['user_type'],
                    'otp' => $this->data['otp']
                ]
            );
    }

}
