<?php

namespace App\Modules\User\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
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
        $subject = 'Welcome Email';
        $name = config('mail.from.name');

        return $this->view('User::emails.welcome_email')
            ->from($address, $name)
            ->subject($subject)
            ->with(
                [
                    'name' => $this->data['user']['name'],
                    'userType' => $this->data['user_type'],
                    'loginLink' => $this->data['login_link'],
                    'loginEmail' => $this->data['user']['login_email'],
                    'loginPassword' => $this->data['login_password']
                ]
            );
    }
}
