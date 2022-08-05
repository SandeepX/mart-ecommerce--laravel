<?php


namespace App\Modules\AlpasalWarehouse\Controllers\Api\Warehouse;

use App\Http\Controllers\Controller;
use App\Modules\AlpasalWarehouse\Services\WarehouseProductPriceService;
use App\Modules\Product\Models\ProductUnitPackageDetail;
use Exception;
use Illuminate\Http\Request;

class WarehouseProductPriceControllerApi extends Controller
{

    private $warehouseProductPriceService;

    public function __construct(WarehouseProductPriceService $warehouseProductPriceService){

        $this->warehouseProductPriceService= $warehouseProductPriceService;
    }
    public function getWarehouseProductPriceHistories(Request $request,$warehouseProductMasterCode){
        try{

            $warehouseCode = getAuthWarehouseCode();
            $warehouseProductPriceDetail =$this->warehouseProductPriceService->getWarehouseProductPriceHistories($warehouseProductMasterCode,$warehouseCode);

            $productPriceHistories = $warehouseProductPriceDetail['price_histories'];
            $productDetail = $warehouseProductPriceDetail['product_detail'];

            $productPriceHistories = $productPriceHistories->map(function($productPriceHistory) use ($productDetail){
                $productPackagingDetail = ProductUnitPackageDetail::where('product_code',$productDetail->product_code)
                    ->where('product_variant_code',$productDetail->product_variant_code)->first();
                if (!$productPackagingDetail){
                    throw new Exception('Product packaging details not found for product '. $productDetail->product_code);
                }
                if ($productPackagingDetail->macro_to_super_value){
                    $microValue=$productPackagingDetail->macro_to_super_value * $productPackagingDetail->unit_to_macro_value *$productPackagingDetail->micro_to_unit_value;
                    $productPriceHistory['mrp'] = $productPriceHistory['mrp'] *$microValue;
                }elseif ($productPackagingDetail->unit_to_macro_value){
                    $productPriceHistory['mrp'] = $productPriceHistory['mrp'] *($productPackagingDetail ->unit_to_macro_value *$productPackagingDetail ->micro_to_unit_value);
                }
                elseif ($productPackagingDetail->micro_to_unit_value){
                    $productPriceHistory['mrp'] = $productPriceHistory['mrp'] *$productPackagingDetail ->micro_to_unit_value;
                }else{
                    $productPriceHistory['mrp'] = $productPriceHistory['mrp'];
                }
                return $productPriceHistory;
            });
            //return  $productPriceHistories;
            if ($request->ajax()) {
                return view('AlpasalWarehouse::warehouse.warehouse-products.show-partials.price-history-table',
                    compact('productPriceHistories','productDetail'))->render();
            }
            return $productPriceHistories;
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }
    public function getWarehouseProductPriceInfo(Request $request,$warehouseProductMasterCode){
        try{
            $warehouseCode = getAuthWarehouseCode();

            $warehouseProductDetail = $this->warehouseProductPriceService->getWarehouseProductPriceInfo($warehouseProductMasterCode,$warehouseCode);
            //return  $productPriceHistories;
           //dd($warehouseProductDetail);

            $packagingInfo=[];
            if($warehouseProductDetail->has_product_variants){
                $warehouseProductDetail['product_variants'] = $warehouseProductDetail->product_variants->map(function($productVariant) use ($packagingInfo){
                    $productPackagingDetail = ProductUnitPackageDetail::where('product_code',$productVariant->product_code)
                        ->where('product_variant_code',$productVariant->product_variant_code)->first();
//                    if (!$productPackagingDetail){
//                        throw new Exception('Product packaging details not found for product '. $productVariant->product_code);
//                    }
                    if ( $productPackagingDetail && $productVariant->warehouseProductPriceMaster) {
                        if ($productPackagingDetail->macro_to_super_value) {
                            $microValue = $productPackagingDetail->macro_to_super_value * $productPackagingDetail->unit_to_macro_value * $productPackagingDetail->micro_to_unit_value;
                            $productVariant->warehouseProductPriceMaster->mrp = $productVariant->warehouseProductPriceMaster->mrp * $microValue;
                        } elseif ($productPackagingDetail->unit_to_macro_value) {
                            $productVariant->warehouseProductPriceMaster->mrp = $productVariant->warehouseProductPriceMaster->mrp * ($productPackagingDetail->unit_to_macro_value * $productPackagingDetail->micro_to_unit_value);
                        } elseif ($productPackagingDetail->micro_to_unit_value) {
                            $productVariant->warehouseProductPriceMaster->mrp = $productVariant->warehouseProductPriceMaster->mrp * $productPackagingDetail->micro_to_unit_value;
                        } else {
                            $productVariant->warehouseProductPriceMaster->mrp = $productVariant->warehouseProductPriceMaster->mrp;
                        }


                        if ($productPackagingDetail->super_unit_code) {
                            $toBePushed = '1 ' . $productPackagingDetail->superPackageType->package_name . ' = ' .
                                $productPackagingDetail->macro_to_super_value . ' ' .
                                $productPackagingDetail->macroPackageType->package_name . '';

                            $toBePushed = $toBePushed . '(1 ' . $productPackagingDetail->superPackageType->package_name . ' = ' .
                                $productPackagingDetail->unit_to_macro_value *
                                $productPackagingDetail->macro_to_super_value . ' ' .
                                $productPackagingDetail->unitPackageType->package_name . ') ';

                            $toBePushed = $toBePushed . '(1 ' . $productPackagingDetail->superPackageType->package_name . ' = ' .
                                $productPackagingDetail->micro_to_unit_value *
                                $productPackagingDetail->unit_to_macro_value *
                                $productPackagingDetail->macro_to_super_value . ' ' .
                                $productPackagingDetail->microPackageType->package_name . ')';
                            array_push($packagingInfo, $toBePushed);
                        }

                        if ($productPackagingDetail->macro_unit_code) {
                            $toBePushed = '1 ' . $productPackagingDetail->macroPackageType->package_name . ' = ' .
                                $productPackagingDetail->unit_to_macro_value . ' ' .
                                $productPackagingDetail->unitPackageType->package_name . '';

                            $toBePushed = $toBePushed . '(1 ' . $productPackagingDetail->macroPackageType->package_name . ' = ' .
                                $productPackagingDetail->micro_to_unit_value *
                                $productPackagingDetail->unit_to_macro_value . ' ' .
                                $productPackagingDetail->microPackageType->package_name . ')';

                            array_push($packagingInfo, $toBePushed);
                        }

                        if ($productPackagingDetail->unit_code) {
                            $toBePushed = '1 ' . $productPackagingDetail->unitPackageType->package_name . ' = ' .
                                $productPackagingDetail->micro_to_unit_value . ' ' .
                                $productPackagingDetail->microPackageType->package_name . '';
                            array_push($packagingInfo, $toBePushed);
                        }
                    }

                    $productVariant->packaging_info = $packagingInfo;
                    return $productVariant;
                });
            }
            else {
                $productPackagingDetail = ProductUnitPackageDetail::where('product_code', $warehouseProductDetail->product_code)
                    ->where('product_variant_code', null)->first();
//                if (!$productPackagingDetail){
//                    throw new Exception('Product packaging details not found for product '. $warehouseProductDetail->product_code);
//                }
                if ($productPackagingDetail) {

                    if ($productPackagingDetail->macro_to_super_value) {
                        $microValue = $productPackagingDetail->macro_to_super_value * $productPackagingDetail->unit_to_macro_value * $productPackagingDetail->micro_to_unit_value;
                        $warehouseProductDetail->warehouseProductPriceMaster->mrp = $warehouseProductDetail->warehouseProductPriceMaster->mrp * $microValue;
                    } elseif ($productPackagingDetail->unit_to_macro_value) {
                        $warehouseProductDetail->warehouseProductPriceMaster->mrp = $warehouseProductDetail->warehouseProductPriceMaster->mrp * ($productPackagingDetail->unit_to_macro_value * $productPackagingDetail->micro_to_unit_value);
                    } elseif ($productPackagingDetail->micro_to_unit_value) {
                        $warehouseProductDetail->warehouseProductPriceMaster->mrp = $warehouseProductDetail->warehouseProductPriceMaster->mrp * $productPackagingDetail->micro_to_unit_value;
                    } else {
                        $warehouseProductDetail->warehouseProductPriceMaster->mrp = $warehouseProductDetail->warehouseProductPriceMaster->mrp;
                    }


                    if ($productPackagingDetail->super_unit_code) {
                        $toBePushed = '1 ' . $productPackagingDetail->superPackageType->package_name . ' = ' .
                            $productPackagingDetail->macro_to_super_value . ' ' .
                            $productPackagingDetail->macroPackageType->package_name . '';

                        $toBePushed = $toBePushed . '(1 ' . $productPackagingDetail->superPackageType->package_name . ' = ' .
                            $productPackagingDetail->unit_to_macro_value *
                            $productPackagingDetail->macro_to_super_value . ' ' .
                            $productPackagingDetail->unitPackageType->package_name . ') ';

                        $toBePushed = $toBePushed . '(1 ' . $productPackagingDetail->superPackageType->package_name . ' = ' .
                            $productPackagingDetail->micro_to_unit_value *
                            $productPackagingDetail->unit_to_macro_value *
                            $productPackagingDetail->macro_to_super_value . ' ' .
                            $productPackagingDetail->microPackageType->package_name . ')';
                        array_push($packagingInfo, $toBePushed);
                    }

                    if ($productPackagingDetail->macro_unit_code) {
                        $toBePushed = '1 ' . $productPackagingDetail->macroPackageType->package_name . ' = ' .
                            $productPackagingDetail->unit_to_macro_value . ' ' .
                            $productPackagingDetail->unitPackageType->package_name . '';

                        $toBePushed = $toBePushed . '(1 ' . $productPackagingDetail->macroPackageType->package_name . ' = ' .
                            $productPackagingDetail->micro_to_unit_value *
                            $productPackagingDetail->unit_to_macro_value . ' ' .
                            $productPackagingDetail->microPackageType->package_name . ')';

                        array_push($packagingInfo, $toBePushed);
                    }

                    if ($productPackagingDetail->unit_code) {
                        $toBePushed = '1 ' . $productPackagingDetail->unitPackageType->package_name . ' = ' .
                            $productPackagingDetail->micro_to_unit_value . ' ' .
                            $productPackagingDetail->microPackageType->package_name . '';
                        array_push($packagingInfo, $toBePushed);
                    }
                }

                    $warehouseProductDetail->packaging_info = $packagingInfo;
                }



           // dd($warehouseProductDetail['product_variants']);
            if ($request->ajax()) {
                return view('AlpasalWarehouse::warehouse.warehouse-products.show-partials.price-info-table',
                    compact('warehouseProductDetail'))->render();
            }
            return $warehouseProductDetail;
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

}
