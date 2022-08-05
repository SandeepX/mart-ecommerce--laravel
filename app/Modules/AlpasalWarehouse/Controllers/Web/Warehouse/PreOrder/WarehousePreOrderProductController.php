<?php


namespace App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\PreOrder;


use App\Modules\AlpasalWarehouse\Helpers\PreOrder\WarehousePreOrderHelper;
use App\Modules\AlpasalWarehouse\Helpers\PreOrder\WarehousePreOrderProductFilter;
use App\Modules\AlpasalWarehouse\Requests\PreOrder\ClonePreOrderProductsByVendorCodeRequest;
use App\Modules\AlpasalWarehouse\Requests\PreOrder\WarehousePreOrderPriceSettingCreateRequest;
use App\Modules\AlpasalWarehouse\Requests\PreOrder\WarehousePreOrderProductPriceUpdateRequest;
use App\Modules\AlpasalWarehouse\Requests\PreOrder\WarehousePreOrderProductsStatusChangeRequest;
use App\Modules\AlpasalWarehouse\Services\PreOrder\WarehousePreOrderProductService;
use App\Modules\AlpasalWarehouse\Services\PreOrder\WarehousePreOrderService;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Brand\Services\BrandService;
use App\Modules\Category\Services\CategoryService;
use App\Modules\Product\Helpers\ProductPriceHelper;
use App\Modules\Product\Models\ProductUnitPackageDetail;
use App\Modules\Product\Services\ProductService;
use App\Modules\Product\Services\ProductVariantService;
use App\Modules\Vendor\Services\VendorService;
use Illuminate\Http\Request;

use Exception;
use Illuminate\Support\Facades\DB;

class WarehousePreOrderProductController extends BaseController
{
    public $title = 'Alpasal Warehouse PreOrder';
    public $base_route = 'warehouse.warehouse-pre-orders.';
    public $sub_icon = 'file';
    public $module = 'AlpasalWarehouse::';

    private $view='warehouse.warehouse-pre-orders.';

    private $warehousePreOrderService,$vendorService,$categoryService,$brandService;
    private $productService,$warehousePreOrderProductService;
    private $productVariantService;

    public function __construct(WarehousePreOrderService $warehousePreOrderService,
                                VendorService $vendorService,
                                CategoryService $categoryService,
                                BrandService $brandService,
                                ProductService $productService,
                                WarehousePreOrderProductService $warehousePreOrderProductService,
                                ProductVariantService $productVariantService
    )
    {
        $this->middleware('permission:View Added Products Of WH Pre Order', ['only' => [
            'listVendorsInPreOrders',
            'getPreOrderProductsList'
        ]]);
        $this->middleware('permission:Add Products To WH Pre Order', ['only' => 'addProductsPage']);
        $this->middleware('permission:Change Status Of Products Of Vendor', ['only' => 'toggleStatusVendorsProductsInPreOrders']);
        $this->middleware('permission:Change Status Of Variants Of Product', ['only' => 'changeStatusOfallVariantsinProduct']);
        $this->middleware('permission:Clone Warehouse Products', ['only' => 'cloneWarehouseProductsByListingCode']);
        $this->middleware('permission:Clone Vendor Products', ['only' => 'cloneVendorProductsByListingCode']);
        $this->middleware('permission:Edit Product Variant Price Of Pre Order', ['only' => [
            'editProductPriceSettingForPreOrder',
            'updateProductPriceSettingForPreOrder',
            'togglePreOrderProductStatus'
        ]]);

        $this->warehousePreOrderService = $warehousePreOrderService;
        $this->vendorService = $vendorService;
        $this->categoryService = $categoryService;
        $this->brandService = $brandService;
        $this->productService = $productService;
        $this->warehousePreOrderProductService= $warehousePreOrderProductService;
        $this->productVariantService = $productVariantService;
    }

    public function getPreOrderProductsList(Request $request,$warehousePreOrderListingCode,$vendorCode){
        try{
            $filterParameters = [
                'warehouse_code' =>getAuthWarehouseCode(),
                'warehouse_preorder_listing_code' => $warehousePreOrderListingCode,
                'product_name' => $request->get('product_name'),
                'vendor_code' => $vendorCode
            ];

        //    dd('done');

            $with=['product','productVariant'];
            $warehousePreOrder = $this->warehousePreOrderService->findOrFailWarehousePreOrderByWarehouseCode($warehousePreOrderListingCode,getAuthWarehouseCode());


            $warehousePreOrderProducts = WarehousePreOrderProductFilter::filterPaginatedWarehouseGroupedPreOrderProducts(
                $filterParameters,20,$with);

            //dd($warehousePreOrderProducts);

            $vendor = $this->vendorService->findOrFailVendorByCode($vendorCode);


            return view($this->loadViewData($this->module.$this->view.'show-products'),compact(
                'warehousePreOrderProducts','warehousePreOrder','filterParameters','warehousePreOrderListingCode','vendor'));
        }catch (Exception $exception){
            return redirect()->route($this->base_route.'index')->with('danger', $exception->getMessage());
        }
    }

    public function addProductsPage(Request $request,$warehousePreOrderListingCode){

      // dd($warehousePreOrderListingCode);
        try{

            $warehousePreOrder = $this->warehousePreOrderService->findOrFailWarehousePreOrderByWarehouseCode($warehousePreOrderListingCode,getAuthWarehouseCode());
             if ($warehousePreOrder->isPastFinalizationTime()){
                throw new Exception('Cannot add products after finalization time.');
             }

            if ($warehousePreOrder->isCancelled()){
                throw new Exception('Cannot add products: pre-order was cancelled.');
            }

            $vendors = $this->vendorService->getAllActiveVendors();
            $categories = $this->categoryService->getCategoryMaster(['category_code','category_name']);
            $brands = $this->brandService->getAllBrands();
            $authWarehouseCode= getAuthWarehouseCode();

            $filterParameters = [
                'warehouse_code' => $authWarehouseCode,
                'warehouse_preorder_listing_code' => $warehousePreOrderListingCode,
                'vendor_name'=>$request->get('vendor_name'),
                'product_name'=>$request->get('product_name')
            ];


            $warehousePreOrderProducts= $this->warehousePreOrderService->getPaginatedGroupedProductsOfWarehousePreOrder($filterParameters,20);
            //dd($warehousePreOrderProducts);

            return view($this->loadViewData($this->module.$this->view.'add-products'),compact(
                'warehousePreOrder',
                'vendors',
                'categories','brands','warehousePreOrderProducts','filterParameters','warehousePreOrderListingCode'));
        }catch (Exception $exception){
            return redirect()->route($this->base_route.'index')->with('danger', $exception->getMessage());
        }
    }

    public function getProductPriceSettingFormForPreOrder(Request $request,$warehousePreOrderCode,$productCode){
        try{
            $warehousePreOrder = $this->warehousePreOrderService->findOrFailWarehousePreOrderByWarehouseCode($warehousePreOrderCode,getAuthWarehouseCode());
            if ($warehousePreOrder->isPastFinalizationTime()){
                throw new Exception('Cannot add product after finalization time.');
            }

            if ($warehousePreOrder->isCancelled()){
                throw new Exception('Cannot add product: pre-order was cancelled.');
            }
            if (WarehousePreOrderHelper::doesPreOrderConsistProduct($warehousePreOrderCode,$productCode)){
                throw new Exception('Product already added to preorder.');
            }
            $product= $this->productService->findOrFailProductByCodeWith($productCode,['productVariants']);
            $productVariants =[];
            $hasVariants = false;
            $packagingInfo=[];
            if ($product->hasVariants()){
                $productVariants = $product->productVariants;
                $hasVariants= true;

                $productVariants = $productVariants->map(function ($productVariant) use ($product,$packagingInfo){
                    $with=['microPackageType', 'unitPackageType','macroPackageType','superPackageType'];
                    $productPackagingDetail = ProductUnitPackageDetail::with($with)->where('product_code',$product->product_code)
                        ->where('product_variant_code',$productVariant->product_variant_code)->first();
                    if ($productPackagingDetail){
                        if ($productPackagingDetail->super_unit_code){
                            $toBePushed = '1 ' . $productPackagingDetail->superPackageType->package_name . ' = ' .
                                $productPackagingDetail->macro_to_super_value . ' ' .
                                $productPackagingDetail->macroPackageType->package_name.'';

                            $toBePushed=$toBePushed.'(1 ' . $productPackagingDetail->superPackageType->package_name . ' = ' .
                                $productPackagingDetail->unit_to_macro_value *
                                $productPackagingDetail->macro_to_super_value . ' ' .
                                $productPackagingDetail->unitPackageType->package_name.') ';

                            $toBePushed=$toBePushed.'(1 ' . $productPackagingDetail->superPackageType->package_name . ' = ' .
                                $productPackagingDetail->micro_to_unit_value *
                                $productPackagingDetail->unit_to_macro_value *
                                $productPackagingDetail->macro_to_super_value . ' ' .
                                $productPackagingDetail->microPackageType->package_name.')';
                            array_push($packagingInfo,$toBePushed);
                        }

                        if ($productPackagingDetail->macro_unit_code){
                            $toBePushed = '1 ' . $productPackagingDetail->macroPackageType->package_name . ' = ' .
                                $productPackagingDetail->unit_to_macro_value . ' ' .
                                $productPackagingDetail->unitPackageType->package_name.'';

                            $toBePushed=$toBePushed.'(1 ' . $productPackagingDetail->macroPackageType->package_name . ' = ' .
                                $productPackagingDetail->micro_to_unit_value *
                                $productPackagingDetail->unit_to_macro_value . ' ' .
                                $productPackagingDetail->microPackageType->package_name.')';

                            array_push($packagingInfo,$toBePushed);
                        }

                        if ($productPackagingDetail->unit_code){
                            $toBePushed = '1 ' . $productPackagingDetail->unitPackageType->package_name . ' = ' .
                                $productPackagingDetail->micro_to_unit_value . ' ' .
                                $productPackagingDetail->microPackageType->package_name.'';
                            array_push($packagingInfo,$toBePushed);
                        }
                    }

                    $productVariant->packaging_info= $packagingInfo;

                    return $productVariant;
                });
            }
            else{

                $productPackagingDetail = ProductUnitPackageDetail::where('product_code',$product->product_code)
                    ->where('product_variant_code',null)->first();
                if ($productPackagingDetail){
                    if ($productPackagingDetail->super_unit_code){
                        $toBePushed = '1 ' . $productPackagingDetail->superPackageType->package_name . ' = ' .
                            $productPackagingDetail->macro_to_super_value . ' ' .
                            $productPackagingDetail->macroPackageType->package_name.'';

                        $toBePushed=$toBePushed.'(1 ' . $productPackagingDetail->superPackageType->package_name . ' = ' .
                            $productPackagingDetail->unit_to_macro_value *
                            $productPackagingDetail->macro_to_super_value . ' ' .
                            $productPackagingDetail->unitPackageType->package_name.') ';

                        $toBePushed=$toBePushed.'(1 ' . $productPackagingDetail->superPackageType->package_name . ' = ' .
                            $productPackagingDetail->micro_to_unit_value *
                            $productPackagingDetail->unit_to_macro_value *
                            $productPackagingDetail->macro_to_super_value . ' ' .
                            $productPackagingDetail->microPackageType->package_name.')';
                        array_push($packagingInfo,$toBePushed);
                    }

                    if ($productPackagingDetail->macro_unit_code){
                        $toBePushed = '1 ' . $productPackagingDetail->macroPackageType->package_name . ' = ' .
                            $productPackagingDetail->unit_to_macro_value . ' ' .
                            $productPackagingDetail->unitPackageType->package_name.'';

                        $toBePushed=$toBePushed.'(1 ' . $productPackagingDetail->macroPackageType->package_name . ' = ' .
                            $productPackagingDetail->micro_to_unit_value *
                            $productPackagingDetail->unit_to_macro_value . ' ' .
                            $productPackagingDetail->microPackageType->package_name.')';

                        array_push($packagingInfo,$toBePushed);
                    }

                    if ($productPackagingDetail->unit_code){
                        $toBePushed = '1 ' . $productPackagingDetail->unitPackageType->package_name . ' = ' .
                            $productPackagingDetail->micro_to_unit_value . ' ' .
                            $productPackagingDetail->microPackageType->package_name.'';
                        array_push($packagingInfo,$toBePushed);
                    }
                }
                $product->packaging_info= $packagingInfo;
            }
            if ($request->ajax()) {
                return view('AlpasalWarehouse::warehouse.warehouse-pre-orders.add-products-partials.price-setting-form',
                    compact('product','productVariants','hasVariants'))->render();
            }
            // return $products;
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function setProductPriceSettingForPreOrder(WarehousePreOrderPriceSettingCreateRequest $request,$warehousePreOrderCode,$productCode){

        try{

            $validatedData = $request->validated();
            foreach(array_filter($validatedData['mrp']) as $key => $mrp){

                $productVariantCode= isset($validatedData['product_variant_code'][$key])?$validatedData['product_variant_code'][$key] : null;

                $productPackagingDetail = ProductUnitPackageDetail::where('product_code',$productCode)
                    ->where('product_variant_code',$productVariantCode)->first();
                if (!$productPackagingDetail){
                    throw new Exception('Product packaging detail not found for product '. $productCode);
                }

                //// checking for negative value
                $marginValues = [];
                $marginValues = [
                    'admin_margin_type' => $validatedData['admin_margin_type'][$key],
                    'admin_margin_value' => $validatedData['admin_margin_value'][$key] ,
                    'wholesale_margin_type' => $validatedData['wholesale_margin_type'][$key],
                    'wholesale_margin_value' => $validatedData['wholesale_margin_value'][$key],
                    'retail_store_margin_type' => $validatedData['retail_margin_type'][$key],
                    'retail_store_margin_value' => $validatedData['retail_margin_value'][$key]
                ];

                $productName =  $this->productService->findOrFailProductByCode($productCode)
                    ->product_name;
                if($productVariantCode){
                    $variantName = $this->productVariantService->findOrFailVariantByProductCodeAndVariantCode(
                        $productCode, $productVariantCode
                    )->product_variant_name;
                }

                if(!ProductPriceHelper::checkNegativeProductPrice($mrp,$marginValues)){
                    throw new Exception('Margin Value For Product: '.$productName.'
                    '.( isset($variantName) ? $variantName : '') .' exceeds than MRP. Cannot add Negative Price');
                }

                // ends here

                if ($productPackagingDetail->macro_to_super_value){
                    $microValue=$productPackagingDetail->macro_to_super_value * $productPackagingDetail->unit_to_macro_value *$productPackagingDetail->micro_to_unit_value;
                    $validatedData['mrp'][$key] = $validatedData['mrp'][$key] /$microValue;
                }elseif ($productPackagingDetail->unit_to_macro_value){
                    $validatedData['mrp'][$key] = $validatedData['mrp'][$key] /($productPackagingDetail ->unit_to_macro_value *$productPackagingDetail ->micro_to_unit_value);
                }
                elseif ($productPackagingDetail->micro_to_unit_value){
                    $validatedData['mrp'][$key] = $validatedData['mrp'][$key] /$productPackagingDetail ->micro_to_unit_value;
                }else{
                    $validatedData['mrp'][$key] = $validatedData['mrp'][$key];
                }
            }

            $this->warehousePreOrderService->storeProductsPriceSettingForPreOrder($validatedData,$warehousePreOrderCode,$productCode);

            /*  $warehousePreOrderProducts= $this->warehousePreOrderService->getPaginatedProductsOfWarehousePreOrder($warehousePreOrderCode,$authWarehouseCode,20);
              if ($request->ajax()) {
                  //$request->session()->flash('success', 'Product added to pre-order successfully');
                  return view('AlpasalWarehouse::warehouse.warehouse-pre-orders.add-products-partials.pre-order-products-tbl',
                      compact('warehousePreOrderProducts'))->render();
              }*/
            return sendSuccessResponse('Product added to pre-order successfully',$warehousePreOrderCode);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }

    }

    public function viewProductPriceSettingForPreOrder(Request $request,$warehousePreOrderCode,$productCode){
        try{
            $with='warehousePreOrderProducts';
            $warehousePreOrder = $this->warehousePreOrderService->findOrFailWarehousePreOrderByWarehouseCode($warehousePreOrderCode,getAuthWarehouseCode(),$with);
            $product= $this->productService->findOrFailProductByCodeWith($productCode,['productVariants']);

            $warehousePreOrderProducts= WarehousePreOrderHelper::getPreOrderProductsWithPrice($warehousePreOrderCode,$productCode);

            //temporary
            $packagingInfo=[];
            $warehousePreOrderProducts= array_map(function($priceList) use ($packagingInfo){
                $productPackagingDetail = ProductUnitPackageDetail::where('product_code',$priceList->product_code)
                    ->where('product_variant_code',$priceList->product_variant_code)->first();
                if (!$productPackagingDetail){
                    throw new Exception('Product packaging details not found for product '. $priceList->product_code);
                }
                if ($productPackagingDetail->macro_to_super_value){
                    $microValue=$productPackagingDetail->macro_to_super_value * $productPackagingDetail->unit_to_macro_value *$productPackagingDetail->micro_to_unit_value;
                    $priceList->mrp = $priceList->mrp *$microValue;
                }elseif ($productPackagingDetail->unit_to_macro_value){
                    $priceList->mrp = $priceList->mrp *($productPackagingDetail ->unit_to_macro_value *$productPackagingDetail ->micro_to_unit_value);
                }
                elseif ($productPackagingDetail->micro_to_unit_value){
                    $priceList->mrp = $priceList->mrp *$productPackagingDetail ->micro_to_unit_value;
                }else{
                    $priceList->mrp = $priceList->mrp;
                }

                if ($productPackagingDetail->super_unit_code){
                    $toBePushed = '1 ' . $productPackagingDetail->superPackageType->package_name . ' = ' .
                        $productPackagingDetail->macro_to_super_value . ' ' .
                        $productPackagingDetail->macroPackageType->package_name.'';

                    $toBePushed=$toBePushed.'(1 ' . $productPackagingDetail->superPackageType->package_name . ' = ' .
                        $productPackagingDetail->unit_to_macro_value *
                        $productPackagingDetail->macro_to_super_value . ' ' .
                        $productPackagingDetail->unitPackageType->package_name.') ';

                    $toBePushed=$toBePushed.'(1 ' . $productPackagingDetail->superPackageType->package_name . ' = ' .
                        $productPackagingDetail->micro_to_unit_value *
                        $productPackagingDetail->unit_to_macro_value *
                        $productPackagingDetail->macro_to_super_value . ' ' .
                        $productPackagingDetail->microPackageType->package_name.')';
                    array_push($packagingInfo,$toBePushed);
                }

                if ($productPackagingDetail->macro_unit_code){
                    $toBePushed = '1 ' . $productPackagingDetail->macroPackageType->package_name . ' = ' .
                        $productPackagingDetail->unit_to_macro_value . ' ' .
                        $productPackagingDetail->unitPackageType->package_name.'';

                    $toBePushed=$toBePushed.'(1 ' . $productPackagingDetail->macroPackageType->package_name . ' = ' .
                        $productPackagingDetail->micro_to_unit_value *
                        $productPackagingDetail->unit_to_macro_value . ' ' .
                        $productPackagingDetail->microPackageType->package_name.')';

                    array_push($packagingInfo,$toBePushed);
                }

                if ($productPackagingDetail->unit_code){
                    $toBePushed = '1 ' . $productPackagingDetail->unitPackageType->package_name . ' = ' .
                        $productPackagingDetail->micro_to_unit_value . ' ' .
                        $productPackagingDetail->microPackageType->package_name.'';
                    array_push($packagingInfo,$toBePushed);
                }

                $priceList->packaging_info = $packagingInfo;
                return $priceList;
            },$warehousePreOrderProducts);
            //end of temporary
            //dd($warehousePreOrderProducts);
            if ($request->ajax()) {
                return view('AlpasalWarehouse::warehouse.warehouse-pre-orders.show-products-partials.price-view-table',
                    compact('warehousePreOrderProducts','warehousePreOrder','product'))->render();
            }
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function editProductPriceSettingForPreOrder(Request $request,$warehousePreOrderCode,$productCode){
        //yeiho
        try{
            $with='warehousePreOrderProducts';
            $warehousePreOrder = $this->warehousePreOrderService->findOrFailWarehousePreOrderByWarehouseCode($warehousePreOrderCode,getAuthWarehouseCode(),$with);
            if ($warehousePreOrder->isPastFinalizationTime()){
                throw new Exception('Cannot edit product after finalization time.');
            }

            if ($warehousePreOrder->isCancelled()){
                throw new Exception('Cannot edit product: pre-order was cancelled.');
            }
            $product= $this->productService->findOrFailProductByCodeWith($productCode,['productVariants']);

          $warehousePreOrderProducts= WarehousePreOrderHelper::getPreOrderProductsWithPrice($warehousePreOrderCode,$productCode);

          //temporary
            $packagingInfo=[];
           $warehousePreOrderProducts= array_map(function($priceList) use ($packagingInfo){
               $productPackagingDetail = ProductUnitPackageDetail::where('product_code',$priceList->product_code)
                   ->where('product_variant_code',$priceList->product_variant_code)->first();
               if (!$productPackagingDetail){
                   throw new Exception('Product packaging details not found for product '. $priceList->product_code);
               }
               if ($productPackagingDetail->macro_to_super_value){
                   $microValue=$productPackagingDetail->macro_to_super_value * $productPackagingDetail->unit_to_macro_value *$productPackagingDetail->micro_to_unit_value;
                   $priceList->mrp = $priceList->mrp *$microValue;
               }elseif ($productPackagingDetail->unit_to_macro_value){
                   $priceList->mrp = $priceList->mrp *($productPackagingDetail ->unit_to_macro_value *$productPackagingDetail ->micro_to_unit_value);
               }
               elseif ($productPackagingDetail->micro_to_unit_value){
                   $priceList->mrp = $priceList->mrp *$productPackagingDetail ->micro_to_unit_value;
               }else{
                   $priceList->mrp = $priceList->mrp;
               }

               if ($productPackagingDetail->super_unit_code){
                   $toBePushed = '1 ' . $productPackagingDetail->superPackageType->package_name . ' = ' .
                       $productPackagingDetail->macro_to_super_value . ' ' .
                       $productPackagingDetail->macroPackageType->package_name.'';

                   $toBePushed=$toBePushed.'(1 ' . $productPackagingDetail->superPackageType->package_name . ' = ' .
                       $productPackagingDetail->unit_to_macro_value *
                       $productPackagingDetail->macro_to_super_value . ' ' .
                       $productPackagingDetail->unitPackageType->package_name.') ';

                   $toBePushed=$toBePushed.'(1 ' . $productPackagingDetail->superPackageType->package_name . ' = ' .
                       $productPackagingDetail->micro_to_unit_value *
                       $productPackagingDetail->unit_to_macro_value *
                       $productPackagingDetail->macro_to_super_value . ' ' .
                       $productPackagingDetail->microPackageType->package_name.')';
                   array_push($packagingInfo,$toBePushed);
               }

               if ($productPackagingDetail->macro_unit_code){
                   $toBePushed = '1 ' . $productPackagingDetail->macroPackageType->package_name . ' = ' .
                       $productPackagingDetail->unit_to_macro_value . ' ' .
                       $productPackagingDetail->unitPackageType->package_name.'';

                   $toBePushed=$toBePushed.'(1 ' . $productPackagingDetail->macroPackageType->package_name . ' = ' .
                       $productPackagingDetail->micro_to_unit_value *
                       $productPackagingDetail->unit_to_macro_value . ' ' .
                       $productPackagingDetail->microPackageType->package_name.')';

                   array_push($packagingInfo,$toBePushed);
               }

               if ($productPackagingDetail->unit_code){
                   $toBePushed = '1 ' . $productPackagingDetail->unitPackageType->package_name . ' = ' .
                       $productPackagingDetail->micro_to_unit_value . ' ' .
                       $productPackagingDetail->microPackageType->package_name.'';
                   array_push($packagingInfo,$toBePushed);
               }
               $priceList->packaging_info=$packagingInfo;
               return $priceList;
           },$warehousePreOrderProducts);
            //end of temporary

           // dd($warehousePreOrderProducts);
            if ($request->ajax()) {
                return view('AlpasalWarehouse::warehouse.warehouse-pre-orders.add-products-partials.price-update-form',
                    compact('warehousePreOrderProducts','warehousePreOrder','product'))->render();
            }
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function updateProductPriceSettingForPreOrder(WarehousePreOrderProductPriceUpdateRequest $request,$warehousePreOrderCode,$productCode){

        try{
            $validatedData = $request->validated();

            //temporary
            $productVariantCode= isset($validatedData['product_variant_code'])?$validatedData['product_variant_code'] : null;

            $productPackagingDetail = ProductUnitPackageDetail::where('product_code',$productCode)
                ->where('product_variant_code',$productVariantCode)->first();
            if (!$productPackagingDetail){
                throw new Exception('Product packaging detail not found for product '. $productCode);
            }

            //// checking for negative value
            $marginValues = [];
            $marginValues = [
                'admin_margin_type' => $validatedData['admin_margin_type'],
                'admin_margin_value' => $validatedData['admin_margin_value'],
                'wholesale_margin_type' => $validatedData['wholesale_margin_type'],
                'wholesale_margin_value' =>  $validatedData['wholesale_margin_value'],
                'retail_store_margin_type' => $validatedData['retail_margin_type'],
                'retail_store_margin_value' => $validatedData['retail_margin_value']
            ];

            $productName =  $this->productService->findOrFailProductByCode($productCode)
                ->product_name;
            if($productVariantCode){
                $variantName = $this->productVariantService->findOrFailVariantByProductCodeAndVariantCode(
                    $productCode, $productVariantCode
                )->product_variant_name;
            }

            if(!ProductPriceHelper::checkNegativeProductPrice($validatedData['mrp'],$marginValues)){
                throw new Exception('Margin Value For Product: '.$productName.'
                    '.( isset($variantName) ? $variantName : '') .' exceeds than MRP. Cannot add Negative Price');
            }

            // ends here



            if ($productPackagingDetail->macro_to_super_value){
                $microValue=$productPackagingDetail->macro_to_super_value * $productPackagingDetail->unit_to_macro_value *$productPackagingDetail->micro_to_unit_value;
                $validatedData['mrp'] = $validatedData['mrp'] /$microValue;
            }elseif ($productPackagingDetail->unit_to_macro_value){
                $validatedData['mrp'] = $validatedData['mrp'] /($productPackagingDetail ->unit_to_macro_value *$productPackagingDetail ->micro_to_unit_value);
            }
            elseif ($productPackagingDetail->micro_to_unit_value){
                $validatedData['mrp'] = $validatedData['mrp'] /$productPackagingDetail ->micro_to_unit_value;
            }else{
                $validatedData['mrp'] = $validatedData['mrp'];
            }
            //end of temporary
            //dd($validatedData);
            $this->warehousePreOrderService->updateProductPriceSettingForPreOrder($validatedData,$warehousePreOrderCode,$productCode);

            return sendSuccessResponse('Product price updated successfully',$warehousePreOrderCode);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function deletePreOrderProduct($warehousePreOrderCode,$warehousePreOrderProductCode)
    {
        try{
            throw new Exception('Cannot Delete Products :( ');
            $this->warehousePreOrderProductService->deleteWarehousePreOrderProduct($warehousePreOrderCode,$warehousePreOrderProductCode);
            return sendSuccessResponse('Product deleted successfully',$warehousePreOrderCode);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function togglePreOrderProductStatus(Request $request,$warehousePreOrderCode,$preOrderProductCode){
        try{
            $warehousePreOrderProduct=$this->warehousePreOrderProductService->updateActiveStatus($warehousePreOrderCode,$preOrderProductCode);
            $status=false;
            if ($warehousePreOrderProduct->is_active){
                $status = true;
            }
            $responseData=[
              'status' => $status,
              'warehouse_preorder_code'=>$warehousePreOrderCode
            ];

            if($request->ajax()){
                return sendSuccessResponse('Product status updated successfully',$responseData);
            }
            return redirect()->back()->with('success','Warehouse Products Status Changed Successfully');

        }catch (Exception $exception){
            if($request->ajax()) {
                return sendErrorResponse($exception->getMessage(), $exception->getCode());
            }else{
                return redirect()->back()->with('danger', $exception->getMessage());
            }
        }
    }

    public function cloneWarehouseProductsByListingCode($preOrderListingCode){

        try {

            $warehouseCode = getAuthWarehouseCode();
            $createdBy = getAuthUserCode();
            $warehousePreOrder = $this->warehousePreOrderService->findOrFailWarehousePreOrderByCode($preOrderListingCode);
            if ($warehousePreOrder->isPastEndTime()){
                throw new Exception('End Time Completed of This Pre Order.Cannot add products after End time');
            }

            DB::beginTransaction();
            $preorderProducts = $this->warehousePreOrderProductService->cloneWarehouseProductsByListingCode($warehouseCode,$preOrderListingCode,$createdBy);
            DB::commit();
            return redirect()->back()->with('success','Warehouse Products Cloned into Pre Order successfully');
        }catch(Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }

    }


    public function cloneVendorProductsByListingCode(ClonePreOrderProductsByVendorCodeRequest $request,$preOrderListingCode){

        try{
            $validatedData = $request->validated();
            $validatedData['preOrderListingCode'] = $preOrderListingCode;
            $validatedData['created_by'] = getAuthUserCode();

            DB::beginTransaction();
            $warehousePreOrder = $this->warehousePreOrderService->findOrFailWarehousePreOrderByCode($preOrderListingCode);
            if ($warehousePreOrder->isPastEndTime()){
                throw new Exception('End Time Completed of Destination Pre Order.Cannot add products after End time');
            }

            $clonedProducts = $this->warehousePreOrderProductService->cloneProductsFromVendorToPreOrderListing($validatedData);
            $vendor = $this->vendorService->findOrFailVendorByCode($validatedData['vendor_code']);
            DB::commit();
            return $request->session()->flash('success','All Products From Vender to Pre Order Cloned Successfully , From:'.$vendor->vendor_name);
        }catch (Exception $exception){
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function deletePreOrderProductByProductCode($warehousePreOrderListingCode,$preOrderProductCode)
    {
        try{
            $this->warehousePreOrderProductService->deletePreOrderProductByProductCode($warehousePreOrderListingCode,$preOrderProductCode);
            return redirect()->back()->with('success', 'Preorder product Removed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function deleteAllProductsOfPreOrder($warehousePreorderListingCode)
    {
        try{
            throw new Exception('this feature not in use');
            DB::beginTransaction();
            $this->warehousePreOrderProductService->deleteAllPreOrderProducts($warehousePreorderListingCode);
            DB::commit();
            return redirect()->back()->with('success', 'Preorder All products Removed Successfully');
        }catch (\Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function changeStatusOfPreOrderProducts(WarehousePreOrderProductsStatusChangeRequest $request)
    {
        $validatedData = $request->validated();

        try{
            $warehousePreOrderListing =  $this->warehousePreOrderService->findOrFailWarehousePreOrderByCode($validatedData['warehouse_preorder_listing_code']);

            if($warehousePreOrderListing->isFinalized()){
                throw new Exception('Cannot Change Status After Pre Order Listing Is Finalized');
            }

            $updateStatus = $this->warehousePreOrderProductService->changeAllWarehousePreorderProductStatus($validatedData);

            return $request->session()->flash('success','Warehouse All Preorder Product status changed successfully');
        }catch(\Exception $exception){
            return $request->session()->flash('error','Warehouse All  Preorder Product status change Unsuccessful');
        }

    }


    public function listVendorsInPreOrders(Request $request,$warehousePreOrderListingCode){

        try{

            $filterParameters=[
                'vendor_name'=>$request->vendor_name
            ];

            $warehousePreOrderListing = $this->warehousePreOrderService->findOrFailWarehousePreOrderByCode($warehousePreOrderListingCode);

            $warehouseCode =  $warehousePreOrderListing->warehouse_code;

            $vendors = WarehousePreOrderHelper::getVendorsInvolvedInWarehousePreOrdersForAdmin($warehousePreOrderListingCode,$filterParameters);
            return view($this->loadViewData($this->module.$this->view.'vendors-list'),compact(
                'vendors','filterParameters','warehousePreOrderListingCode','warehousePreOrderListing','warehouseCode'));

        }catch (Exception $exception){
            return redirect()->route('warehouse.warehouse-pre-orders.index')->with('danger', $exception->getMessage());
        }
    }

    public function toggleStatusVendorsProductsInPreOrders($warehousePreOrderListingCode,$vendorCode,$status){

        try{
            $updateStatus = $this->warehousePreOrderProductService->changeAllWarehousePreorderProductStatusofVendor($warehousePreOrderListingCode,$vendorCode,$status);
            return redirect()->back()->with('success','Warehouse All Preorder Product status changed successfully');
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }

    }

    public function changeStatusOfallVariantsinProduct($warehousePreOrderCode,$productCode,$status){

          try{
              $updateStatus = $this->warehousePreOrderProductService->changeStatusOfallVariantsinProduct($warehousePreOrderCode,$productCode,$status);
              return redirect()->back()->with('success','Warehouse Preorder Product status changed successfully');
          }catch (Exception $exception){
              return redirect()->back()->with('danger', $exception->getMessage());
          }
    }



}
