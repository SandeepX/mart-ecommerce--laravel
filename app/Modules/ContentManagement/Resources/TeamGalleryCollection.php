<?php


namespace App\Modules\ContentManagement\Resources;

use App\Modules\ContentManagement\Resources\TeamGalleryResource;
use Illuminate\Http\Resources\Json\ResourceCollection;


class TeamGalleryCollection extends ResourceCollection
{

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        return TeamGalleryResource::collection($this->collection);
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function with($request)
    {
        return [
            'error' => false,
            'code' => 200
        ];
    }

}



