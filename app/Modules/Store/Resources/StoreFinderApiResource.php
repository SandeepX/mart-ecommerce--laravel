<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/24/2020
 * Time: 4:39 PM
 */

namespace App\Modules\Store\Resources;


use App\Modules\Store\Helpers\StoreLocationFinder;
use App\Modules\Store\Models\Store;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreFinderApiResource extends JsonResource
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
            // 'id' => $this->id,
            'store_name' => $this->store_name,
            'store_code' => $this->store_code,
            'store_location_code'=>$this->store_location_code,
            'location'=>$this->getFullLocationPath(2),
            'store_ward'=>$this->location->location_name,
            'store_logo' => photoToUrl($this->store_logo,asset('uploads/stores/logos'))
        ];
    }

}
