<?php

namespace App\Modules\PaymentProcessor\Console\Commands;

use App\Modules\PaymentProcessor\Services\PaymentGatewayService;
use Illuminate\Console\Command;


class TestPayment extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'test:payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testing Payment.';

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
    public function handle(PaymentGatewayService $paymentGatewayService)
    {
        try{

            $paymentGatewayService->setDefaultPaymentGateway('connect_ips');
            $data = $paymentGatewayService->processPayment(
               [

               ]
            );

        }catch (\Exception $exception){
           dd('Exception : ' . ($exception->getMessage()));
        }

         dd($data);
    }

}
