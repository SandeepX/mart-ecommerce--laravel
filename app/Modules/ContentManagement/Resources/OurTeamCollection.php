<?php


namespace App\Modules\ContentManagement\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Modules\ContentManagement\Resources\OurTeamResource;

class OurTeamCollection extends ResourceCollection
{

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        return OurTeamResource::collection($this->collection)->collection->groupBy('department');
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



