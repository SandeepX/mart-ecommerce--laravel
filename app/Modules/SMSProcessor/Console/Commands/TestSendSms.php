<?php

namespace App\Modules\SMSProcessor\Console\Commands;

use App\Modules\SMSProcessor\Services\SMSService;
use Illuminate\Console\Command;


class TestSendSms extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'send:sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testing Sms.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(SMSService $smsService)
    {
        try{

            $smsService->setDefaultSmsProvider('sparrow_sms');
            $data = $smsService->sendSMS(
                convertToArray('9865852942'),
                'Bulk SMS'
            );

        }catch (\Exception $exception){
           dd('Exception' . ($exception->getMessage()));
        }

         dd($data);
    }

}
