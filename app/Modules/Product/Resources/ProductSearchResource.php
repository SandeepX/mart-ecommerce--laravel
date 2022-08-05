<?php


namespace App\Modules\Product\Resources;


use App\Modules\Product\Helpers\ProductPriceHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ProductSearchResource extends JsonResource
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
            'product_code' => $this->product_code,
            'product_name' => $this->product_name,
            'slug' => $this->slug,
          //  'variants_count'=>$this->product_variants_count,
            'featured_image' => $this->getFeaturedImage(),
            'price' => $this->when(
                (Auth::guard('api')->check()) && Auth::guard('api')->user()->isStoreUser(),
                (new ProductPriceHelper())->getProductStorePriceRange($this->product_code)
            ),
            'brand' => $this->brand->brand_name,
            'category'=>$this->category->category_name,
           // 'price' => (new ProductPriceHelper())->getProductStorePriceRange($this->product_code)

        ];
    }

}
