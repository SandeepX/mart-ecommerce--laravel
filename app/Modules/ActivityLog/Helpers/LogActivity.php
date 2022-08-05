<?php


namespace App\Modules\ActivityLog\Helpers;
use Request;
//use Illuminate\Http\Request;
use App\Modules\ActivityLog\Models\ActivityLog;

class LogActivity
{


    public static function addToLog($subject,array $data=[],$authCode=null)
    {
        $log = [];
        $log['subject'] = $subject;
        $log['url'] = Request::fullUrl();
        $log['method'] = Request::method();
        $log['ip'] = Request::ip();
        $log['agent'] = Request::header('user-agent');
        $log['user_code'] = auth()->check() ?getAuthUserCode() : $authCode;
        $log['data']= empty($array) ? null : json_encode($data);
        ActivityLog::create($log);
    }


    public static function logActivityLists()
    {
        return ActivityLog::latest()->get();
    }


}
