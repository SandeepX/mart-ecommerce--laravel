<?php

namespace App\Modules\ManagerDiary\Resources\Diary;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ManagerDiaryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => ManagerDiaryResource::collection($this->collection),
            'links' => [
                'self' => 'link-value',
            ],
        ];
    }

    public function with($request)
    {
        return [
            'error' => false,
            'code' => 200
        ];
    }
}
