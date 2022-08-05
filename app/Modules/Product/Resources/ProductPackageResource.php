<?php

namespace App\Modules\Product\Resources;
use App\Modules\Brand\Resources\BrandResource;
use App\Modules\Category\Resources\CategoryResource;
use App\Modules\Product\Resources\ProductSensitivity\ProductSensitivityResource;
use App\Modules\Product\Resources\ProductWarranty\ProductWarrantyResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductPackageResource extends JsonResource
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
            'package_name' => $this->packageType->package_name,
            'package_code' => $this->package_code,
            'package_weight' => $this->package_weight,
            'package_length' => $this->package_length,
            'package_width' => $this->package_width,
            'package_height' => $this->package_height,
            'units_per_package' => $this->units_per_package,
        ];
    }
}
