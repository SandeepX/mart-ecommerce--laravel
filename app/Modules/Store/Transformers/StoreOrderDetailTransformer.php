<?php

namespace App\Modules\Store\Transformers;


use App\Modules\Product\Models\ProductMaster;
use App\Modules\Product\Models\ProductVariant;
use App\Modules\Product\Resources\MinimalProductResource;
use App\Modules\Product\Resources\ProductVariantResource;
use App\Modules\Store\Models\StoreOrder;
use App\Modules\Store\Models\StoreOrderDetails;

class StoreOrderDetailTransformer
{

    private $storeOrder,$storeOrderDetail;

    public function __construct(StoreOrder $storeOrder,StoreOrderDetails $storeOrderDetail)
    {
        $this->storeOrder = $storeOrder;
        $this->storeOrderDetail = $storeOrderDetail;
    }

    public function transform(){



       $acceptanceStatusHideCondition = ($this->storeOrder->delivery_status == 'under-verification'
                                        ) ? true : false;


      // $productVariant = new ProductVariant((array)$this->storeOrderDetail->productVariant);
        $product = $this->storeOrderDetail->product;
        $productVariant = $this->storeOrderDetail->productVariant;


       $orderDetails = [
            'store_order_detail_code' =>  $this->storeOrderDetail->store_order_detail_code,
           'product' => [
               'product_name' => $product->product_name,
               'slug' => $product->slug,
               'is_taxable' =>$product->isTaxable(),
               'featured_image' => $product->getFeaturedImage(),
           ],
            'product_variant' => [
               // 'product_variant_code' => $productVariant->product_variant_code,
                'product_variant_name' => $productVariant->id,
            ],
            'quantity' =>  $this->storeOrderDetail->quantity,
            'initial_order_quantity' =>  $this->storeOrderDetail->initial_order_quantity,
            'unit_rate' => ( $this->storeOrderDetail->unit_rate),
            'sub_total' => ( $this->storeOrderDetail->quantity * ( $this->storeOrderDetail->unit_rate) ),
            'is_accepted' =>  $this->storeOrderDetail->is_accepted
        ];

       if(!$acceptanceStatusHideCondition){
           $orderDetails['acceptance_status'] = $this->storeOrderDetail->acceptance_status;
       }
       return $orderDetails;
    }
}
