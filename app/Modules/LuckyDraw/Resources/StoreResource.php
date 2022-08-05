<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/24/2020
 * Time: 4:39 PM
 */

namespace App\Modules\LuckyDraw\Resources;

use App\Modules\Location\Repositories\LocationHierarchyRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
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

                'store_code' => $this->store_code,
                'store_name' => $this->store_name,
                'is_active' => $this->is_active,
                'is_approved' => $this->is_approved,
                'store_full_location'=>$this->store_full_location,
                'store_logo' => photoToUrl($this->store_logo, asset('uploads/stores/logos')),
                'purchase_meet' => $this->purchase_eligibility,
                'total_purchased_price' => $this->total_purchased_price,
                'eligibility'=>$this->eligibility,
                'is_winner'=>isset($this->is_winner) ? $this->is_winner : 0
            ];

        return $result;
    }

}
