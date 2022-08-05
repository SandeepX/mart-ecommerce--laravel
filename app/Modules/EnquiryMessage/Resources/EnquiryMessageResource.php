<?php

namespace App\Modules\EnquiryMessage\Resources;

use App\Modules\EnquiryMessage\Models\EnquiryMessage;
use Illuminate\Http\Resources\Json\JsonResource;

class EnquiryMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'department' => $this->department,
            'store_message_code' => $this->store_message_code,
            'subject' => $this->subject,
            'message' => $this->message,
            'sender_code' => $this->sender_code,
            'sender'=>$this->senderUser ? $this->senderUser->name : 'No Name (Admin)',
            'is_seen'=>$this->is_seen,
            'receiver_code' => $this->receiver_code,
            'created_at' => $this->created_at,
//            'parentMessages'=>EnquiryMessage::where('store_message_code',$this->parent_id)->first(),


        ];
    }
}
