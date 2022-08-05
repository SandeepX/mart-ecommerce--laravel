<?php

namespace App\Modules\User\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordChangedEmail extends Mailable
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
        //
        $this->data= $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = config('mail.from.address');
        $subject = 'Password Changed';
        $name = config('mail.from.name');

        return $this->view('User::emails.password_change_email')
            ->from($address,$name)
            ->subject($subject)
            ->with(
                [
                    'name' => $this->data['name'],
                    'userType' => $this->data['user_type'],
                    'loginLink' => $this->data['login_link'],
                    'loginEmail' => $this->data['login_email'],
                    'loginPassword' => $this->data['login_password']
                ]
            );
    }
}
