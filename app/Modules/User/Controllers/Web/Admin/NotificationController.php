<?php

namespace App\Modules\User\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\User\Helpers\UserNotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Auth;

class NotificationController extends BaseController
{

    public $title = 'Notification';
    public $base_route = 'admin.';
    public $sub_icon = 'file';
    public $module = 'User::';

    private $view='admin.';

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $paginateBy = 10;
        if(Auth::check())
        {
            $notifications = UserNotificationHelper::getUserNotifications($paginateBy);
            return view(Parent::loadViewData($this->module.$this->view.'notifications.index'),compact('notifications'));
        }

        return collet();
    }


}
