<?php

namespace App\Modules\EnquiryMessage\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EnquiryMessageInboxResource extends JsonResource
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
            'store_message_code' => $this->store_message_code,
            'subject' => $this->subject,
            'message' => $this->message,
            'sender'=>($this->senderUser) ? $this->senderUser->name : 'No Name ',
            'is_seen'=>$this->is_seen,
            'created_at' => $this->created_at,

        ];
    }
}
