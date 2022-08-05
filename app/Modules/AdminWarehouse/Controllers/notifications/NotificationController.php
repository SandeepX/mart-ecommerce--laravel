<?php

namespace App\Modules\AdminWarehouse\Controllers\notifications;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\User\Helpers\UserNotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Auth;

class NotificationController extends BaseController
{

    public $title = 'Notification';
    public $base_route = 'warehouse.';
    public $sub_icon = 'file';
    public $module = 'AdminWarehouse::';

    private $view='notifications.';

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {

      //  dd('done');
        $paginateBy = 10;
        if(Auth::check())
        {
            $notifications = UserNotificationHelper::getUserNotifications($paginateBy);

            //dd($this->module.$this->view.'index');
            return view(Parent::loadViewData($this->module.$this->view.'index'),compact('notifications'));
        }

        return collet();
    }


}
