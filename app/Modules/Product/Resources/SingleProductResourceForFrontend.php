<?php

namespace App\Modules\Product\Resources;
use App\Modules\Brand\Resources\BrandResource;
use App\Modules\Cart\Helpers\CartHelper;
use App\Modules\Category\Resources\CategoryResource;
use App\Modules\Product\Helpers\ProductPriceHelper;
use App\Modules\Product\Resources\ProductSensitivity\ProductSensitivityResource;
use App\Modules\Product\Resources\ProductWarranty\ProductWarrantyDetailResource;
use App\Modules\Product\Utilities\ProductPackagingFormatter;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleProductResourceForFrontend extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $hasProductPackagingDetail = $this->product_packaging_detail ? true : false;
        $productPriceHelper = new ProductPriceHelper();
        $productRate = null;
        if ($hasProductPackagingDetail) {
            $productRate = $productPriceHelper->getProductAuthStorePrice($this->product_code);//without Rs
        }

        $integerMicroStock = (int)$this->warehouse_product_stock;
        $productDetails = [
            'product_code' => $this->product_code,
            'product_name' => $this->product_name,
            'slug' => $this->slug,
            'description' => $this->description,
            'brand' => [
                'brand_name' => $this->brand->brand_name,
            ],
            'category' => [
                'category_name' => $this->category->category_name,
            ],
            'sensitivity' => [
                'sensitivity_name' => $this->sensitivity->sensitivity_name
            ],
            'warranty' => [
                'warranty_name' => $this->warrantyDetail->productWarranty->warranty_name,
                'warranty_policy' => $this->warrantyDetail->warranty_policy,
            ],

//            'package' => [
//               'package_name'=>$this->package->packageType->package_name,
//               'package_weight'=> $this->package->package_weight,
//               'package_length'=> $this->package->package_length,
//               'package_width'=> $this->package->package_width,
//               'package_height'=> $this->package->package_height,
//               'units_per_package'=> $this->package->units_per_package,
//            ],


            'product_images' => $this->images->map(function ($image) {
                return ['image' => photoToUrl($image->image, url('uploads/products'))];
            }),
            'product_variants' => ProductVariantResource::collection($this->productVariants),
            'product_variant_info' => $this->getMainVariantsInProduct(),
            'first_variant' => $this->first_variant,
            'remarks' => $this->remarks,
            'video_link' => $this->video_link == null ? '' : 'https://www.youtube.com/embed/' . $this->video_link,
            'highlights' => json_decode($this->highlights),
            'variant_tag' => $this->hasVariants(),
            //  'category_type_code' => $this->category_type_code,
            'rate' => $productPriceHelper->getProductStorePriceRange($this->product_code),
            'is_taxable' => $this->isTaxable(),
            'warehouse_product_stock' => $this->warehouse_product_stock,
            'rating' => $this->rating,
            //'unit_packaging_details'=>$this->product_packaging_types,

        ];

        $productDetails['product_packaging_types'] = [];
        if ($hasProductPackagingDetail) {
            $productPackagingFormatter = new ProductPackagingFormatter();

            if ($this->product_packaging_detail->micro_unit_code &&
                !in_array('micro', $this->disabled_unit_list)
            ) {

                $stockPerPackage = $this->warehouse_product_stock;
                $packageStock = intval($stockPerPackage);

                if ($stockPerPackage >= 1) {

                    $arr[1] = $this->product_packaging_detail->micro_unit_name;

                    $calculatedStock = $productPackagingFormatter->formatPackagingCombination(
                        $integerMicroStock, array_reverse($arr, true));
                } else {
                    $calculatedStock = 0;
                }

                $packagingInfo = [
                    'package_code' => $this->product_packaging_detail->micro_unit_code,
                    'package_name' => $this->product_packaging_detail->micro_unit_name,
                    'price' => roundPrice($productRate),
                    //'stock' =>$this->warehouse_product_stock,
                    'stock' => $packageStock,
                    'display_stock' => $calculatedStock,
                    'description' => ''
                ];
                if (!is_null($this->user_code)) {
                    $stockInCart = CartHelper::getQuantityAddedInCart(
                        $this->user_code,
                        $this->warehouse_code,
                        $this->product_code,
                        $this->product_packaging_detail->micro_unit_code,
                        null
                    );
                    $packagingInfo['stock_in_cart'] = $stockInCart;
                }


                array_push($productDetails['product_packaging_types'], $packagingInfo);
            }
            if ($this->product_packaging_detail->unit_code &&
                !in_array('unit', $this->disabled_unit_list)
            ) {
                if ($productRate != 'N/A') {
                    $price = roundPrice($this->product_packaging_detail->micro_to_unit_value * $productRate);
                } else {
                    $price = $productRate;
                }

                $stockPerPackage = $this->warehouse_product_stock / $this->product_packaging_detail->micro_to_unit_value;
                $packageStock = intval($stockPerPackage);
                if ($stockPerPackage >= 1) {

                    $arrKey = intval($this->product_packaging_detail->micro_to_unit_value);
                    $arr[$arrKey] = $this->product_packaging_detail->unit_name;


                    $calculatedStock = $productPackagingFormatter->formatPackagingCombination(
                        $this->warehouse_product_stock, array_reverse($arr, true));
                } else {
                    $calculatedStock = 0;
                }

                $packagingInfo = [
                    'package_code' => $this->product_packaging_detail->unit_code,
                    'package_name' => $this->product_packaging_detail->unit_name,
                    'price' => $price,
                    'stock' => $packageStock,
                    'display_stock' => $calculatedStock,
                    'description' => 'One ' . $this->product_packaging_detail->unit_name . ' consists ' .
                        $this->product_packaging_detail->micro_to_unit_value . ' ' .
                        $this->product_packaging_detail->micro_unit_name
                ];
                if (!is_null($this->user_code)) {
                    $stockInCart = CartHelper::getQuantityAddedInCart(
                        $this->user_code,
                        $this->warehouse_code,
                        $this->product_code,
                        $this->product_packaging_detail->unit_code,
                        null
                    );
                    $packagingInfo['stock_in_cart'] = $stockInCart;
                }


                array_push($productDetails['product_packaging_types'], $packagingInfo);
            }
            if ($this->product_packaging_detail->macro_unit_code &&
                !in_array('macro', $this->disabled_unit_list)
            ) {
                if ($productRate != 'N/A') {
                    $price = roundPrice($this->product_packaging_detail->micro_to_unit_value *
                        $this->product_packaging_detail->unit_to_macro_value * $productRate);
                } else {
                    $price = $productRate;
                }

                $stockPerPackage = $this->warehouse_product_stock / ($this->product_packaging_detail->micro_to_unit_value *
                        $this->product_packaging_detail->unit_to_macro_value);
                $packageStock = intval($stockPerPackage);
                if ($stockPerPackage >= 1) {
                    if ($this->product_packaging_detail->macro_unit_code) {
                        $arrKey = intval($this->product_packaging_detail->micro_to_unit_value *
                            $this->product_packaging_detail->unit_to_macro_value);
                        $arr[$arrKey] = $this->product_packaging_detail->macro_unit_name;
                    }

                    $calculatedStock = $productPackagingFormatter->formatPackagingCombination(
                        $this->warehouse_product_stock, array_reverse($arr, true));
                } else {
                    $calculatedStock = 0;
                }


                $packagingInfo = [
                    'package_code' => $this->product_packaging_detail->macro_unit_code,
                    'package_name' => $this->product_packaging_detail->macro_unit_name,
                    'price' => $price,
                    'stock' => $packageStock,
                    'display_stock' => $calculatedStock,
                    'description' => 'One ' . $this->product_packaging_detail->macro_unit_name . ' consists ' .
                        $this->product_packaging_detail->micro_to_unit_value *
                        $this->product_packaging_detail->unit_to_macro_value . ' ' .
                        $this->product_packaging_detail->micro_unit_name
                ];
                if (!is_null($this->user_code)) {
                    $stockInCart = CartHelper::getQuantityAddedInCart(
                        $this->user_code,
                        $this->warehouse_code,
                        $this->product_code,
                        $this->product_packaging_detail->macro_unit_code,
                        null
                    );
                    $packagingInfo['stock_in_cart'] = $stockInCart;
                }
                array_push($productDetails['product_packaging_types'], $packagingInfo);

            }
            if ($this->product_packaging_detail->super_unit_code &&
                !in_array('super', $this->disabled_unit_list)
            ) {
                if ($productRate != 'N/A') {
                    $price = roundPrice($this->product_packaging_detail->micro_to_unit_value *
                        $this->product_packaging_detail->unit_to_macro_value *
                        $this->product_packaging_detail->macro_to_super_value * $productRate);
                } else {
                    $price = $productRate;
                }

                $stockPerPackage = $this->warehouse_product_stock / ($this->product_packaging_detail->micro_to_unit_value *
                        $this->product_packaging_detail->unit_to_macro_value *
                        $this->product_packaging_detail->macro_to_super_value);
                $packageStock = intval($stockPerPackage);
                if ($stockPerPackage >= 1) {
                    if ($this->product_packaging_detail->super_unit_code) {

                        $arrKey = intval($this->product_packaging_detail->micro_to_unit_value *
                            $this->product_packaging_detail->unit_to_macro_value *
                            $this->product_packaging_detail->macro_to_super_value);

                        $arr[$arrKey] = $this->product_packaging_detail->super_unit_name;
                    }

                    //$arr=array_reverse($arr,true);
                    $calculatedStock = $productPackagingFormatter->formatPackagingCombination(
                        $this->warehouse_product_stock, array_reverse($arr, true));
                } else {
                    $calculatedStock = 0;
                }

                $packagingInfo = [
                    'package_code' => $this->product_packaging_detail->super_unit_code,
                    'package_name' => $this->product_packaging_detail->super_unit_name,
                    'price' => $price,
                    'stock' => $packageStock,
                    'display_stock' => $calculatedStock,
                    'description' => 'One ' . $this->product_packaging_detail->super_unit_name . ' consists ' .
                        $this->product_packaging_detail->micro_to_unit_value *
                        $this->product_packaging_detail->unit_to_macro_value *
                        $this->product_packaging_detail->macro_to_super_value . ' ' .
                        $this->product_packaging_detail->micro_unit_name
                ];
                if (!is_null($this->user_code)) {
                    $stockInCart = CartHelper::getQuantityAddedInCart(
                        $this->user_code,
                        $this->warehouse_code,
                        $this->product_code,
                        $this->product_packaging_detail->super_unit_code,
                        null
                    );
                    $packagingInfo['stock_in_cart'] = $stockInCart;
                }

                array_push($productDetails['product_packaging_types'], $packagingInfo);

            }
            //dd($arr);

            if (isset($this->order_qty_limits) && count($this->order_qty_limits) > 0) {
                $productDetails['order_qty_limits'] = $this->order_qty_limits;
            }

        }
        return $productDetails;
    }
}
