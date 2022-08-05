<?php

namespace App\Modules\Cart\Resources;

use App\Modules\AlpasalWarehouse\Helpers\WarehouseProductStockHelper;
use App\Modules\Cart\Models\Cart;
use App\Modules\Product\Helpers\ProductPriceHelper;
use App\Modules\Product\Helpers\ProductUnitPackagingHelper;
use App\Modules\Product\Models\ProductMaster;
use App\Modules\Product\Models\ProductUnitPackageDetail;
use App\Modules\Product\Resources\MinimalProductResource;
use App\Modules\Product\Resources\ProductVariantResource;
use App\Modules\Product\Services\ProductPriceService;
use App\Modules\Product\Utilities\ProductPackagingFormatter;
use Illuminate\Http\Resources\Json\JsonResource;

use Exception;
class CartResource extends JsonResource
{

    // private  $productPriceService;

    // public function __construct($resource,ProductPriceService $productPriceService)
    // {
    //     parent::__construct($resource);
    //     $this->productPriceService = $productPriceService;
    // }


    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        $with=['microPackageType','unitPackageType','macroPackageType','superPackageType'];
        $productVariantCode = $this->product_variant_code ?? null;
        $productVariantName = $this->product_variant_code ? $this->product_variant_name :'';
        $productUnitPackagingDetail = ProductUnitPackageDetail::with($with)
            ->where('product_code',$this->product_code)
            ->where('product_variant_code',$productVariantCode)
            ->where(function ($query){
                $query->where('micro_unit_code',$this->package_code)
                    ->orWhere('unit_code', $this->package_code)
                    ->orWhere('macro_unit_code', $this->package_code)
                    ->orWhere('super_unit_code', $this->package_code);
            })->first();
        if (!$productUnitPackagingDetail){
            throw new Exception('Packaging detail not found for the product '.$this->product_name.$productVariantName);
        }
        $productPackagingFormatter = new ProductPackagingFormatter();

       /* if ($productUnitPackagingDetail->micro_unit_code){
            $arr[1] =$productUnitPackagingDetail->microPackageType->package_name;
        }
        if ($productUnitPackagingDetail->unit_code){
            $arrKey = intval($productUnitPackagingDetail->micro_to_unit_value);
            $arr[$arrKey] =$productUnitPackagingDetail->unitPackageType->package_name;
        }
        if ($productUnitPackagingDetail->macro_unit_code){
            $arrKey = intval($productUnitPackagingDetail->micro_to_unit_value *
                $productUnitPackagingDetail->unit_to_macro_value);
            $arr[$arrKey] =$productUnitPackagingDetail->macroPackageType->package_name;
        }
        if ($productUnitPackagingDetail->super_unit_code){
            $arrKey = intval($productUnitPackagingDetail->micro_to_unit_value *
                $productUnitPackagingDetail->unit_to_macro_value * $productUnitPackagingDetail->macro_to_super_value);
            $arr[$arrKey] =$productUnitPackagingDetail->superPackageType->package_name;
        }*/


        $packageQuantityFromCart =  Cart::where('warehouse_code',$this->warehouse_code)
            ->where('product_code',$this->product_code)
            ->where('product_variant_code',$this->product_variant_code)
            ->where('user_code',$this->user_code)
            ->pluck('quantity','package_code')->toArray();
        $totalCartMicroQuantity=0;
        foreach ($packageQuantityFromCart as $packageCode=>$quantity){
            $totalCartMicroQuantity += ProductUnitPackagingHelper::convertToMicroUnitQuantity(
                $packageCode,$productUnitPackagingDetail,$quantity);
        }
        $totalMicroStock =(int)$this->current_stock - $totalCartMicroQuantity;

        $packageDisplayStock =$totalMicroStock;
        $packageStock =$totalMicroStock;

        if ($productUnitPackagingDetail->micro_unit_code == $this->package_code) {

            $stockPerPackage=$totalMicroStock;
            $packageStock = intval($stockPerPackage);

            $arr[1] =$productUnitPackagingDetail->microPackageType->package_name;

            if ($stockPerPackage >= 1){
                $packageDisplayStock = $productPackagingFormatter->formatPackagingCombination(
                    $totalMicroStock,array_reverse($arr,true));
            }
            else{
                $packageDisplayStock=0;
            }
        }

        elseif ($productUnitPackagingDetail->unit_code == $this->package_code) {
            $arr[1] =$productUnitPackagingDetail->microPackageType->package_name;
            $arrKey = intval($productUnitPackagingDetail->micro_to_unit_value);
            $arr[$arrKey] =$productUnitPackagingDetail->unitPackageType->package_name;

            $stockPerPackage=$totalMicroStock/$productUnitPackagingDetail->micro_to_unit_value;
            $packageStock = intval($stockPerPackage);

            if ($stockPerPackage >= 1){
                $packageDisplayStock = $productPackagingFormatter->formatPackagingCombination(
                    $totalMicroStock,array_reverse($arr,true));
            }
            else{
                $packageDisplayStock=0;
            }
        }
        elseif ($productUnitPackagingDetail->macro_unit_code == $this->package_code) {

            $arr[1] =$productUnitPackagingDetail->microPackageType->package_name;
            $arrKey = intval($productUnitPackagingDetail->micro_to_unit_value);
            $arr[$arrKey] =$productUnitPackagingDetail->unitPackageType->package_name;
            $microValue=$productUnitPackagingDetail->micro_to_unit_value *
                $productUnitPackagingDetail->unit_to_macro_value;
            $arrKey = intval($microValue);
            $arr[$arrKey] =$productUnitPackagingDetail->macroPackageType->package_name;

            $stockPerPackage=$totalMicroStock/$microValue;
            $packageStock = intval($stockPerPackage);
            if ($stockPerPackage >= 1){

                $packageDisplayStock = $productPackagingFormatter->formatPackagingCombination(
                    $totalMicroStock,array_reverse($arr,true));
            }
            else{
                $packageDisplayStock=0;
            }
        }
        elseif ($productUnitPackagingDetail->super_unit_code == $this->package_code) {
            $microValue=$productUnitPackagingDetail->micro_to_unit_value *
                $productUnitPackagingDetail->unit_to_macro_value *
                $productUnitPackagingDetail->macro_to_super_value;

            $arr[1] =$productUnitPackagingDetail->microPackageType->package_name;
            $arrKey = intval($productUnitPackagingDetail->micro_to_unit_value);
            $arr[$arrKey] =$productUnitPackagingDetail->unitPackageType->package_name;
            $arrKey = intval($productUnitPackagingDetail->micro_to_unit_value *
                $productUnitPackagingDetail->unit_to_macro_value);
            $arr[$arrKey] =$productUnitPackagingDetail->macroPackageType->package_name;

            $arrKey = intval($microValue);
            $arr[$arrKey] =$productUnitPackagingDetail->superPackageType->package_name;

            $stockPerPackage=$totalMicroStock/$microValue;
            $packageStock = intval($stockPerPackage);
            if ($stockPerPackage >= 1){

                $packageDisplayStock = $productPackagingFormatter->formatPackagingCombination(
                    $totalMicroStock,array_reverse($arr,true));
            }
            else{
                $packageDisplayStock=0;
            }
        }


        return [
            'cart_code' => $this->cart_code,
            'product' => [
                'product_name' => $this->product_name,
                'product_code' => $this->product_code,
                'slug' => $this->slug,
                'featured_image' =>photoToUrl($this->image, asset((new ProductMaster())->uploadFolder))
            ],
            'product_variant' => !is_null($this->product_variant_code)
                                 ? [
                                     'product_variant_code' =>$this->product_variant_code,
                                     'product_variant_name' => $this->product_variant_name,
                                 ]
                                 : null,
            'warehouse_code'=>$this->warehouse_code,
            //'micro_price' => $this->store_price,
            'price' => $this->ordered_package_price,
            'quantity' => (int)$this->quantity,
            'is_taxable' => $this->is_taxable,
            'micro_stock' =>$totalMicroStock,
            'stock' =>$packageStock,
            'display_stock' =>$packageDisplayStock,
            'is_active'=>$this->is_active,
            'package_name'=>$this->package_name,
            'package_code'=>$this->package_code,
            'total_stock' => $packageStock + (int)$this->quantity
        ];
    }


}
