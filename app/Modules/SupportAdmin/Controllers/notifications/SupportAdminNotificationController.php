<?php


namespace App\Modules\SupportAdmin\Controllers\notifications;


use App\Modules\Application\Controllers\BaseController;
use App\Modules\User\Helpers\UserNotificationHelper;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class SupportAdminNotificationController extends BaseController
{

    public $title = 'Notification';
    public $base_route = 'support-admin.';
    public $sub_icon = 'file';
    public $module = 'SupportAdmin::';

    private $view = 'notifications.';

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $paginateBy = 10;
        if (Auth::check()) {
            $notifications = UserNotificationHelper::getUserNotifications($paginateBy);
            //dd($this->module.$this->view.'index');
            return view(Parent::loadViewData($this->module . $this->view . 'index'), compact('notifications'));
        }

        return collect();
    }


}
