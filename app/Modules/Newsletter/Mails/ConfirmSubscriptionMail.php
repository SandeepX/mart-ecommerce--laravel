<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/1/2020
 * Time: 1:51 PM
 */

namespace App\Modules\Newsletter\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmSubscriptionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->mailData = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = config('mail.from.address');
        $subject = 'Confirm subscription';
        $name = config('mail.from.name');

        return $this->view('Newsletter::emails.confirm_subscription')
            ->from($address, $name)
            ->subject($subject)
            ->with(
                [
                    'action_url' =>$this->mailData['route_link']
                ]
            );
    }
}