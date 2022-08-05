<?php

namespace App\Modules\User\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Modules\Store\Resources\StoreNotification\StoreOrderStatusNotificationResource;
use App\Modules\User\Services\UserNotificationService;
use Exception;
use Illuminate\Support\Facades\DB;

class UserNotificationController extends Controller
{
    private $userNotificationService;
    public function __construct(UserNotificationService $userNotificationService)
    {
        $this->userNotificationService = $userNotificationService;
    }
    
    public function index(){
        try{
            $notifications = $this->userNotificationService->getAllNotifications();
            $notifications = StoreOrderStatusNotificationResource::collection($notifications);
            return sendSuccessResponse('Data Found !', $notifications);

        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
        
    }

    public function markAsRead($notificationId){
        DB::beginTransaction();
        try{
            $this->userNotificationService->markAsRead($notificationId);
            DB::commit();
            return sendSuccessResponse('Notification Marked As Read Successfully');

        }catch(Exception $exception){
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

}