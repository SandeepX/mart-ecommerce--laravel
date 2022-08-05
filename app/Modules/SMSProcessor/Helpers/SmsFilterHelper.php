<?php


namespace App\Modules\SMSProcessor\Helpers;

use App\Modules\SMSProcessor\Models\SmsMaster;

class SmsFilterHelper
{
    public static function getAllSmsByFilter($filterParameters,$with=[])
    {

        $allSmsByfilter = SmsMaster::with($with)->when(isset($filterParameters['sms_code']), function ($query) use ($filterParameters) {
            $query->where('sms_master_code', $filterParameters['sms_code']);
        })
            ->when(isset($filterParameters['purpose']), function ($query) use ($filterParameters) {
                $query->where('purpose', $filterParameters['purpose']);

            })
            ->when(isset($filterParameters['created_from']), function ($query) use ($filterParameters) {
                $query->whereDate('created_at', '>=', date('y-m-d', strtotime($filterParameters['created_from'])));

            })
            ->when(isset($filterParameters['created_to']), function ($query) use ($filterParameters) {
                $query->whereDate('created_at', '<=', date('y-m-d', strtotime($filterParameters['created_to'])));

            })->orderBy('created_at', 'DESC')->paginate(20);

        return $allSmsByfilter;
    }
}

