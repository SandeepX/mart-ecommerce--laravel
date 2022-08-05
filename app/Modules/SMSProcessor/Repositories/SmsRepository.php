<?php


namespace App\Modules\SMSProcessor\Repositories;

use App\Modules\SMSProcessor\Models\SmsMaster;


class SmsRepository
{

    public function store($smsData)
    {
       return SmsMaster::create($smsData)->fresh();
    }

}
