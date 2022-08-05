<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/1/2020
 * Time: 1:38 PM
 */

namespace App\Modules\Newsletter\Jobs;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ConfirmSubscriptionMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $subscriber;
    public $mailData;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($subscriber,$newsLetter)
    {
        $this->subscriber = $subscriber;
        $this->newsLetterData = $newsLetter;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $email = new NewsLetterMail($this->newsLetterData);

        Mail::to($this->subscriber)->send($email);

        //Mail::to( $this->emailAddress)->send(new NewsLetterMail($setting->email));
    }
}