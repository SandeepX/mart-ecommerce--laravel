<?php
/**
 * Created by PhpStorm.
 * User: sandeep
 * Date: 11/1/2020
 * Time: 5:20 PM
 */

namespace App\Modules\GlobalNotification\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'global_notification_code' => $this->global_notification_code,
            'message' => strip_tags($this->message),
            'link' => $this->link,
            'file' => url('uploads/globalNotification/files/' . $this->file),
            'created_for' => $this->created_for,
            'startDate' =>$this->start_date,
            'endDate' =>$this->end_date,
            'created_by' => $this->createdBy->name,
        ];
    }
}





