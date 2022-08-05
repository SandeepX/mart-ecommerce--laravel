<?php

namespace App\Modules\Application\Classes;

use Jenssegers\Agent\Agent;

class UserDeviceDetector
{
    public static function getMobileDeviceInfo(){
        $agent = new Agent();
        $deviceInfo = [];
        $deviceInfo['is_mobile'] = $agent->isMobile();
        $deviceInfo['is_tablet'] = $agent->isTablet();
        $deviceInfo['device_name'] = $agent->device();
        $deviceInfo['platform_name'] = $agent->platform();
        $deviceInfo['user_agent'] = $agent->getUserAgent();
        return $deviceInfo;
    }

}
