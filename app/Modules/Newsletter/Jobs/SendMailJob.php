<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/1/2020
 * Time: 1:45 PM
 */

namespace App\Modules\Newsletter\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $receiverEmail;
    public $mail ;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($receiverEmail, Mailable $mail)
    {
        $this->receiverEmail = $receiverEmail;
        $this->mail = $mail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->receiverEmail)->send($this->mail);
    }

}