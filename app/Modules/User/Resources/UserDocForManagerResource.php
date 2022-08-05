<?php

namespace App\Modules\User\Resources;

use App\Modules\User\Models\UserDoc;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDocForManagerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'document_name' => $this->doc_name,
            'document_number' => $this->doc_number,
            'documentfile' => photoToUrl($this->doc,asset(UserDoc::DOCUMENT_PATH))
        ];
        return $data;
    }
}
