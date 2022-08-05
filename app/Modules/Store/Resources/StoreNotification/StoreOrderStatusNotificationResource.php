<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/24/2020
 * Time: 4:39 PM
 */

namespace App\Modules\Store\Resources\StoreNotification;


use Illuminate\Http\Resources\Json\JsonResource;

class StoreOrderStatusNotificationResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'data' => $this->data,
            'read_at' => $this->read_at,
        ];
    }

}