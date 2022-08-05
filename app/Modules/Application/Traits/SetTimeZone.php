<?php


namespace App\Modules\Application\Traits;

use Carbon\Carbon;
use Carbon\CarbonTimeZone;

trait SetTimeZone
{
    public $tz = 'UTC';

    public function getTz()
    {
      $this->tz = 'Asia/Kathmandu'; // auth()->user->timezone ;
      return $this->tz;
    }

    public function getCreatedAtAttribute($value)
    {
        $date = new Carbon($value);

        $date->setTimezone(new CarbonTimeZone($this->getTz()));

        return $date->format('Y-m-d H:i:s');
    }

    public function getUpdatedAtAttribute($value)
    {
        $date = new Carbon($value);
        $date->setTimezone(new CarbonTimeZone($this->getTz()));
        return $date->format('Y-m-d H:i:s');
    }

    public function getTimeZoneDateTime($value)
    {
        $date = new Carbon($value);
        $date->setTimezone(new CarbonTimeZone($this->getTz()));
        return $date->format('Y-m-d H:i:s');
    }


    /*
     * YOUR_MODEL::whereRaw("CONVERT_TZ(field.updated_at, '+00:00', '{$tzOffset}') BETWEEN '{$startDate}' AND '{$endDate}'")
     * */
    public function getTimeZoneOffSet(){
        return Carbon::createFromTimestamp(0, $this->getTz())->getOffsetString();
    }

}
