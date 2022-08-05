<?php

namespace App\Modules\SalesManager\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ListOfReferredManagerByManagerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $result = [
            'manager_name' => $this->referredManager->manager_name,
            'status' => $this->referredManager->status,
            'is_active' => ($this->referredManager->is_active) ? 'Yes' : 'No'
        ];

        return $result;
    }

}
