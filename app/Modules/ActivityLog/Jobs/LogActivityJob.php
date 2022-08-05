<?php

namespace App\Modules\ActivityLog\Jobs;

use App\Modules\ActivityLog\Helpers\LogActivity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LogActivityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $subject;
    private $data;
    public function __construct($subject,$data=null)
    {
        $this->subject=$subject;
        $this->data=$data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        LogActivity::addToLog($this->subject,$this->data);
    }
}
