<?php


namespace App\Modules\Reporting\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RejectedItemSyncDetailMail extends Mailable implements ShouldQueue
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
        $subject = 'Rejected Item Sync Detail ';
        $name = config('mail.from.name');


        return $this->view('Reporting::admin.emails.rejected-item-mail')
            ->from($address,$name)
            ->subject($subject)
            ->with(
                [
                    'sync_status' => $this->data['sync_status'],
                    'synced_orders_count' => $this->data['synced_orders_count'],
                    'sync_remarks' => $this->data['sync_remarks'],
                    'order_type' => $this->data['order_type'],
                    'sync_started_at' => $this->data['sync_started_at'],
                    'sync_ended_at' => $this->data['sync_ended_at'],
                ]
            );
    }
}


