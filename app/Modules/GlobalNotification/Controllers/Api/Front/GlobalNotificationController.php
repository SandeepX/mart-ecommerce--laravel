<?php


namespace App\Modules\GlobalNotification\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Modules\GlobalNotification\Services\NotificationService;
use App\Modules\GlobalNotification\Resources\NotificationCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth;

class GlobalNotificationController extends Controller
{
    private $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;

    }

    public function getAllNotifications()
    {
        try{

            if( Auth::guard('api')->check()){
                $userType = getAuthParentUserType();
                $notifications = $this->notificationService->getAllNotificationForLoggedInUser($userType);
            }else{
                $notifications = $this->notificationService->getAllNotificationForNotLoggedInUser();
            }

            $allNotification = new NotificationCollection($notifications);
            return sendSuccessResponse('Data Found',  $allNotification);
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }


    }
}
