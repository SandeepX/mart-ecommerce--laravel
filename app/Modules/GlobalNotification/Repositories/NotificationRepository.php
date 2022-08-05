<?php


namespace App\Modules\GlobalNotification\Repositories;

use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\GlobalNotification\Models\GlobalNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;



class NotificationRepository
{

    use ImageService;

//    public function getAllNotification(){
//        return GlobalNotification::orderBy('created_at','DESC')->paginate(15);
//    }

    public function create($notificationData)
    {
        $filename = '';
        try{

            $image = $notificationData['file'];

            $filename = $this->storeImageInServer($image, GlobalNotification::DOCUMENT_PATH);

            $notificationData['file'] = $filename;

            $newNotification = GlobalNotification::create($notificationData)->fresh();

                return $newNotification;

        }catch (Exception $exception){

            $this->deleteImageFromServer(GlobalNotification::DOCUMENT_PATH,$filename);
            throw $exception;
        }
    }

    public function showDetailNotificationByCode($notificationCode)
    {
        try{

            $notificationDetail = GlobalNotification::where('global_notification_code',$notificationCode)->first();

            return $notificationDetail;
        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function delete($notificationCode)
    {
       $image = '';
        try{

            $notificationDetail = GlobalNotification::where('global_notification_code',$notificationCode)->first();
            $image = $notificationDetail['file'];
           // dd($image);
            $this->deleteImageFromServer(GlobalNotification::DOCUMENT_PATH,$image);

            return $notificationDetail->delete();

        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function update($notificationData ,$notificationCode)
    {
       $filename = '';
       $newFilename ='';

        try{
            $notificationDetail = GlobalNotification::where('global_notification_code',$notificationCode)->first();

            $filename = $notificationDetail['file'];

            if (file_exists($notificationData['file'])) {
                $this->deleteImageFromServer(GlobalNotification::DOCUMENT_PATH,$filename);
                $image = $notificationData['file'];
                $newFilename = $this->storeImageInServer($image, GlobalNotification::DOCUMENT_PATH);
                $notificationData['file'] = $newFilename;
            }else{
                $notificationData['file'] = $filename;

            }

           return $notificationDetail->update($notificationData);
        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function toggleStatus($notificationCode)
    {
        $notificationDetail = GlobalNotification::where('global_notification_code',$notificationCode)->first();
        return $notificationDetail->update([
            'is_active' => !$notificationDetail->is_active,
        ]);
    }

    /*******Api method*******/

    public function getActiveNotificationByForTypes($forTypes)
    {
        $presentDate = Carbon::now();
        return GlobalNotification::where('end_date','>=', $presentDate)->where('start_date','<=',$presentDate)
            ->whereIn('created_for',$forTypes)
            ->where('is_active',1)
            ->orderBy('created_at','DESC')->paginate(10);
    }


}
