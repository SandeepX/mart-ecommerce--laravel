<?php

namespace App\Modules\User\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Notification;

class UserNotificationRepository{

    public function findNotificationById($notificationId){
        $notification = auth()->user()->notifications()->find($notificationId);
        if($notification) {
            return $notification;
        }
        throw new ModelNotFoundException('No Such Resource Found!');
    }

    public function getAllNotifications(){
        return auth()->user()->notifications;
    }

    public function markAsRead($notification){
        $notification->markAsRead();
    }
}