<?php

namespace App\Modules\Package\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PackageTypeResource extends JsonResource
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
            'package_code' => $this->package_code,
            'package_name' => $this->package_name,
            'remarks' => $this->remarks
        ];
    }
}
