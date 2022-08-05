<?php

namespace App\Modules\EnquiryMessage\Resources;

use App\Modules\EnquiryMessage\Models\EnquiryMessage;
use Illuminate\Http\Resources\Json\JsonResource;

class EnquiryMessageSingleInboxResource extends JsonResource
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
            'department' => $this->department,
            'parent_id' => $this->parent_id,
            'store_message_code' => $this->store_message_code,
            'subject' => $this->subject,
            'message' => $this->message,
            'sender'=>$this->senderUser->name,
            'is_seen'=>$this->is_seen,
            'created_at' => $this->created_at,
            'parentMessages'=>EnquiryMessage::where('store_message_code',$this->parent_id)->first(),

        ];
    }
}
