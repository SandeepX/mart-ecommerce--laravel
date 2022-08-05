<?php

namespace App\Modules\SalesManager\Mails;

use App\Modules\User\Mails\AccountVerificationEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ManagerEmailVerificationMail extends Mailable
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
        $subject = 'Manager Email Verification Allpasal';
        $name = config('mail.from.name');

        return $this->view('SalesManager::emails.email_verification')
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
