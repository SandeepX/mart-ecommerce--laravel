<?php
/**
 * Created by PhpStorm.
 * User: shramik
 * Date: 5/11/18
 * Time: 3:13 PM
 */

namespace App\Modules\User\Helpers;



class UserNotificationHelper
{
    public static function getUserNotifications($paginateBy = 10)
    {
        $notifications = auth()->user()->notifications()->paginate($paginateBy);
        return $notifications;
    }

}
