<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/29/2020
 * Time: 3:24 PM
 */

namespace App\Modules\Cart\Resources;


use App\Modules\Cart\Models\Cart;
use App\Modules\Product\Services\ProductPriceService;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CartCollection extends ResourceCollection
{

    // protected $productPriceService;
    // /**
    //  * Create a new resource instance.
    //  *
    //  * @param  mixed  $resource
    //  * @return void
    //  */
    // public function __construct($resource, ProductPriceService $productPriceService)
    // {
    //     parent::__construct($resource);
    //     $this->productPriceService = $productPriceService;
    // }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */

    public function toArray($request)
    {

        return  CartResource::collection($this->collection);

        // return [
        //     'data' =>  $this->collection->transform(function (Cart $cart) {
        //         return (new CartResource($cart,$this->productPriceService));
        //     }),

        //   /*  if paginated remove comment
        //   'links' => [
        //         'self' => 'link-value',
        //     ],
        //   */
        // ];

       /* $this->collection->transform(function (Cart $cart) {
            return (new CartResource($cart,$this->productPriceService));
        });

        return parent::toArray($request);*/
    }

    public function with($request)
    {
        return [

            'error' => false,
            'code' => 200
        ];
    }
}
