<?php


namespace App\Modules\GlobalNotification\Helper;

use App\Modules\GlobalNotification\Models\GlobalNotification;

class NotificationFilterHelper
{
    public static function getAllNotificationByFilter($filterParameters)
    {

        $allNotificationByfilter = GlobalNotification:: when(isset($filterParameters['created_for']),function ($query) use($filterParameters){
            $query-> where('created_for',$filterParameters['created_for']);
        })
            ->when(isset($filterParameters['is_active']),function ($query) use($filterParameters){
                $query->where('is_active',$filterParameters['is_active']);

            })

            ->when(isset($filterParameters['start_date']),function ($query) use($filterParameters){
                $query->whereDate('start_date','>=',date('y-m-d',strtotime($filterParameters['start_date'])));

            })

            ->when(isset($filterParameters['end_date']),function ($query) use($filterParameters){
                $query->whereDate('end_date','<=',date('y-m-d',strtotime($filterParameters['end_date'])));

            })->orderBy('created_at','DESC')->paginate(15);

        return $allNotificationByfilter;

    }
}
