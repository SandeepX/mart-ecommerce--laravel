<?php

namespace App\Modules\Store\Resources\StoreOrder;

use App\Modules\Product\Resources\MinimalProductResource;
use App\Modules\Product\Resources\ProductVariantResource;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreOrderDetailResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $storeOrderDeliveryStatus = $this->storeOrder->delivery_status;
        if ($this->productPackageType){
            $packageName=$this->productPackageType->package_name;
        }elseif ($this->product->package){
            $packageName=$this->product->package->packageType->package_name;
        }
        else{
            $packageName ='-';
        }

        $orderDetails = [
            'store_order_detail_code' => $this->store_order_detail_code,
            'product' => new MinimalProductResource($this->product),
            'product_variant' => new ProductVariantResource($this->productVariant),
            'unit_rate' => ($this->unit_rate),
            'quantity' => $this->quantity,
            'initial_order_quantity' => $this->initial_order_quantity,
            'sub_total' =>(
                            $this->quantity * ($this->unit_rate)
                          ),

            //'is_accepted' => $this->is_accepted
            'acceptance_status' => $this->acceptance_status,
            //'package_name' => $this->productPackageType ?$this->productPackageType->package_name :'',
            'package_name' =>$packageName,
            //'old_package_name' => $this->product->package ?$this->product->package->packageType->package_name :'',
        ];


        if( $storeOrderDeliveryStatus == 'accepted'){
           // $orderDetails['acceptance_status'] = NULL;
           // $orderDetails['quantity'] = $this->quantity;
            //$orderDetails['initial_order_quantity'] = NULL;
        }

        if(
            $storeOrderDeliveryStatus == 'processing'
        ){
           // $orderDetails['acceptance_status'] = NULL;
            //$orderDetails['quantity'] = $this->quantity;
           // $orderDetails['initial_order_quantity'] = $this->initial_order_quantity;
            //$orderDetails['sub_total'] = (
                          //                 $this->quantity * ($this->unit_rate)
                          //               );
        }
        return  $orderDetails;
    }

}
