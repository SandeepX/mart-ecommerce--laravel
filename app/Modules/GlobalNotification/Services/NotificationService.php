<?php

namespace App\Modules\GlobalNotification\Services;

use App\Modules\GlobalNotification\Repositories\NotificationRepository;
use DB;
use Exception;
use Carbon\Carbon;



class NotificationService
{

    private $notificationRepository;

    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

//    public function getAllNotification()
//    {
//        $notification = $this->notificationRepository->getallNotification();
//
//        return $notification;
//    }

    public function storeNotification($validated)
    {
        if(isset($validated['is_active'])){
            $notificationData['is_active'] = $validated['is_active'];
        }else{
            $notificationData['is_active'] = 0;
        }
        $notificationData['message'] = $validated['message'];
        $notificationData['link'] = $validated['link'];
        $notificationData['start_date'] = $validated['start_date'];
        $notificationData['end_date'] = $validated['end_date'];
        $notificationData['created_for'] = $validated['created_for'];
        $notificationData['created_by'] = getAuthUserCode();
        $notificationData['file'] = $validated['file'];

        DB::beginTransaction();
        try {
            $notification = $this->notificationRepository->create($notificationData);
            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
        return $notification;

    }

    public function showDetailByCode($notificationCode)
    {
        return $this->notificationRepository->showDetailNotificationByCode($notificationCode);
    }

    public function deleteNotification($notificationCode)
    {
        return $this->notificationRepository->delete($notificationCode);
    }

    public function updateNotification($validated,$notificationCode)
    {

        if(isset($validated['is_active'])){
            $notificationData['is_active'] = $validated['is_active'];
        }else{
            $notificationData['is_active'] = 0;
        }

        if(isset($validated['file'])){
            $notificationData['file'] = $validated['file'];
        }else{
            $notificationData['file'] = null;
        }
        $notificationData['message'] = $validated['message'];
        $notificationData['link'] = $validated['link'];
        $notificationData['start_date'] = $validated['start_date'];
        $notificationData['end_date'] = $validated['end_date'];
        $notificationData['created_for'] = $validated['created_for'];
        $notificationData['created_by'] = getAuthUserCode();

        DB::beginTransaction();
        try {

            $notification = $this->notificationRepository->update($notificationData ,$notificationCode);
            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
        return $notification;

    }

    public function toggleNotification($notificationCode)
    {

        return $this->notificationRepository->toggleStatus($notificationCode);
    }

    /******** Api methods*******/

    //guest user
    public function getAllNotificationForNotLoggedInUser()
    {
        $notifications = $this->notificationRepository->getActiveNotificationByForTypes(['all']);
        return $notifications;
    }

    public function getAllNotificationForLoggedInUser($forType)
    {

        $notifications = $this->notificationRepository->getActiveNotificationByForTypes(['all',$forType]);
        return $notifications;
    }




}

