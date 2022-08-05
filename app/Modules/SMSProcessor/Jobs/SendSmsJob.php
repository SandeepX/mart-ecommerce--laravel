<?php


namespace App\Modules\SMSProcessor\Jobs;

use App\Modules\SMSProcessor\Services\SMSService;
use App\Modules\SystemSetting\Models\GeneralSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSmsJob implements shouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $to, $message,$data;


    public function __construct($to, $message,$data = null)
    {
        $this->to = $to;
        $this->message = $message;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @param SMSService $smsService
     * @return void
     */

    public function handle(SMSService $smsService)
    {
       $generalSetting = GeneralSetting::where('sms_enable',1)->first();
       if($generalSetting){
           return $smsService->sendSMS(convertToArray($this->to),$this->message,$this->data);
       }
    }
}
