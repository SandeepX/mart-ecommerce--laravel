<?php

namespace App\Modules\User\Services;

use App\Modules\User\Repositories\UserNotificationRepository;

class UserNotificationService
{
    private $userNotificationRepository;
    public function __construct(UserNotificationRepository $userNotificationRepository)
    {
        $this->userNotificationRepository = $userNotificationRepository;
    }

    public function getAllNotifications(){
        return $this->userNotificationRepository->getAllNotifications();
    }

    public function markAsRead($notificationId){
        $notification = $this->userNotificationRepository->findNotificationById($notificationId);
        $this->userNotificationRepository->markAsRead($notification);
    }
}