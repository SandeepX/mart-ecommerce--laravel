<?php


namespace App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse;

use App\Modules\AlpasalWarehouse\Helpers\WarehouseProductFilter;
use App\Modules\AlpasalWarehouse\Requests\WarehouseMassProductPriceSettingRequest;
use App\Modules\AlpasalWarehouse\Requests\WarehouseProductChangeStatus;
use App\Modules\AlpasalWarehouse\Requests\WarehouseProductPriceSettingRequest;
use App\Modules\AlpasalWarehouse\Requests\WarehouseWholeProductChangeStatusRequest;
use App\Modules\AlpasalWarehouse\Requests\WarehouseProductOrderLimitRequest;
use App\Modules\AlpasalWarehouse\Services\WarehouseProductService;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\Product\Helpers\ProductFilter;
use App\Modules\Product\Models\ProductUnitPackageDetail;
use App\Modules\Vendor\Services\VendorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Exception;
class WarehouseProductController extends  BaseController
{

    public $title = 'Alpasal Warehouse Product';
    public $base_route = 'warehouse.warehouse-products.';
    public $sub_icon = 'file';
    public $module = 'AlpasalWarehouse::';

    private $view='warehouse.warehouse-products.';

    private $vendorService,$warehouseProductService;

    public function __construct(VendorService $vendorService,WarehouseProductService $warehouseProductService)
    {
        $this->middleware('permission:View List Of Wh Products', ['only' => ['index']]);
        $this->middleware('permission:View WH Product Detail', ['only' => ['show']]);
        $this->middleware('permission:Change WH Product Status', [
            'only' => [
                'productStatusChange',
                'warehouseAllProductsChangeStatus'
            ]
        ]);
        $this->middleware('permission:Price Setting For WH Product', ['only' => ['updatePriceSetting']]);
        $this->middleware('permission:Set Quantity Limit For WH Product', ['only' => ['setWarehouseProductQtyOrderLimit']]);

        $this->vendorService = $vendorService;
        $this->warehouseProductService= $warehouseProductService;

    }
    public function index(Request $request){

        //$productCodes = WarehouseProductFilter::test();

     //   $user = auth()->user();
        //dd($user);
    // return response()->json($user->getPermissionsViaRoles());
        try{

            $filterParameters = [
                'vendor_code' =>  $request->get('vendor'),
                'product_name' =>  $request->get('product_name'),
                'status' =>$request->get('status'),
                'warehouse_code'=>getAuthWarehouseCode()
            ];
            //dd($filterParameters);

            $with=[
                'product',
                'product.package.packageType',
                'product.vendor',
                'product.brand',
                'product.category',
                'product.priceList',
                'product.priceList.productVariant'
            ];
           // $products = ProductFilter::filterPaginatedProducts($filterParameters,25,$with);
            $warehouseProducts= WarehouseProductFilter::filterPaginatedWarehouseProducts($filterParameters,10,$with);

          //dd($warehouseProducts);
            $vendors = $this->vendorService->getAllVendors();

            return view($this->loadViewData($this->module.$this->view.'index'),compact('warehouseProducts', 'vendors','filterParameters'));
        }catch (Exception $exception){
            return redirect()->route('warehouse.dashboard')->with('danger',$exception->getMessage());
        }
    }

    public function show($productCode){

      //  dd(1);
        try{
            $warehouseCode = getAuthWarehouseCode();
            $warehouseProductDetail = $this->warehouseProductService->getWarehouseProductDetail($productCode,$warehouseCode);
            if ($warehouseProductDetail->has_product_variants){
                $warehouseProductDetail->product_variants = $warehouseProductDetail->product_variants->map(function ($productVariant){
                    $productPackagingDetail = ProductUnitPackageDetail::where('product_code',$productVariant->product_code)
                        ->where('product_variant_code',$productVariant->product_variant_code)->first();

                    if ($productPackagingDetail && $productVariant->warehouseProductPriceMaster){
                        //throw new Exception('Product packaging details not found for product '. $productVariant->product_code);

                        if ($productPackagingDetail->macro_to_super_value){
                            $microValue=$productPackagingDetail->macro_to_super_value * $productPackagingDetail->unit_to_macro_value *$productPackagingDetail->micro_to_unit_value;
                            $productVariant->warehouseProductPriceMaster->mrp =$productVariant->warehouseProductPriceMaster->mrp *$microValue;
                        }elseif ($productPackagingDetail->unit_to_macro_value){
                            $productVariant->warehouseProductPriceMaster->mrp =$productVariant->warehouseProductPriceMaster->mrp *($productPackagingDetail ->unit_to_macro_value *$productPackagingDetail ->micro_to_unit_value);
                        }
                        elseif ($productPackagingDetail->micro_to_unit_value){
                            $productVariant->warehouseProductPriceMaster->mrp =$productVariant->warehouseProductPriceMaster->mrp *$productPackagingDetail ->micro_to_unit_value;
                        }else{
                            $productVariant->warehouseProductPriceMaster->mrp =$productVariant->warehouseProductPriceMaster->mrp;
                        }

                    }
                    return $productVariant;

                });
            }
            else{
                $productCode = $warehouseProductDetail->product_code;
                $productVariantCode = $warehouseProductDetail->product_variant_code;

                $productPackagingDetail = ProductUnitPackageDetail::where('product_code',$productCode)
                    ->where('product_variant_code',$productVariantCode)->first();
                if ($productPackagingDetail && $warehouseProductDetail->warehouseProductPriceMaster){
                    //throw new Exception('Product packaging detail not found for product '. $productCode);
                    if ($productPackagingDetail->macro_to_super_value){
                        $microValue=$productPackagingDetail->macro_to_super_value * $productPackagingDetail->unit_to_macro_value *$productPackagingDetail->micro_to_unit_value;
                        $warehouseProductDetail->warehouseProductPriceMaster->mrp =$warehouseProductDetail->warehouseProductPriceMaster->mrp *$microValue;
                    }elseif ($productPackagingDetail->unit_to_macro_value){
                        $warehouseProductDetail->warehouseProductPriceMaster->mrp =$warehouseProductDetail->warehouseProductPriceMaster->mrp *($productPackagingDetail ->unit_to_macro_value *$productPackagingDetail ->micro_to_unit_value);
                    }
                    elseif ($productPackagingDetail->micro_to_unit_value){
                        $warehouseProductDetail->warehouseProductPriceMaster->mrp =$warehouseProductDetail->warehouseProductPriceMaster->mrp *$productPackagingDetail ->micro_to_unit_value;
                    }else{
                        $warehouseProductDetail->warehouseProductPriceMaster->mrp =$warehouseProductDetail->warehouseProductPriceMaster->mrp;
                    }
                }

            }

            return view($this->loadViewData($this->module.$this->view.'show'),compact('warehouseProductDetail'));
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function toggleWPMStatus($wpmCode)
    {
        try{
            $this->warehouseProductService->toggleWPMStatus($wpmCode);
            return redirect()->back()->with('success', 'Status Changed  Successfully');
        }catch(\Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function productStatusChange(WarehouseProductChangeStatus $request)
    {
        $validatedData = $request->validated();
        try{
            $updateStatus = $this->warehouseProductService->changeProductStatus($validatedData);
           return $request->session()->flash('success','Warehouse Product status changed successfully');
        }catch(\Exception $exception){
            return $request->session()->flash('error','Warehouse Product status change Unsuccessful');
        }
    }

    public function warehouseAllProductsChangeStatus(WarehouseWholeProductChangeStatusRequest $request)
    {
        $changeStatusTo = $request->validated();
        try{
            $updateStatus = $this->warehouseProductService->changeAllWarehouseProductStatus($changeStatusTo);
            return $request->session()->flash('success','Warehouse All Product status changed successfully');
        }catch(\Exception $exception){
            return $request->session()->flash('error','Warehouse All Product status change Unsuccessful');
        }

    }

    public function setWarehouseProductQtyOrderLimit(WarehouseProductOrderLimitRequest $request)
    {

        DB::beginTransaction();
        try{
            $validatedData = $request->validated();
            $this->warehouseProductService->setWarehouseProductOrderLimit($validatedData);
            DB::commit();
            return redirect()->back()->with('success', 'Product order Limit Set Successfull');
        }catch(\Exception $exception){
            DB::rollback();
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }


    public function updatePriceSetting(WarehouseProductPriceSettingRequest $priceSettingRequest,
                                       $warehouseProductMasterCode){
        try{
            $validatedData= $priceSettingRequest->validated();
            //temporary upper package price implementation in service
            $this->warehouseProductService->updatePriceSettingOfWarehouseProduct($validatedData,$warehouseProductMasterCode);
            return redirect()->back()->with('success','Price updated successfully');
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }


    public function createMassPriceSettingOfProduct($productCode){
        try{
            $warehouseCode = getAuthWarehouseCode();
            $packagingInfo=[];
            $warehouseProductDetail = $this->warehouseProductService->getWarehouseProductDetail($productCode,$warehouseCode);
            if ($warehouseProductDetail->has_product_variants){
                $warehouseProductDetail->product_variants = $warehouseProductDetail->product_variants->map(function ($productVariant) use ($packagingInfo){
                    $with=['microPackageType', 'unitPackageType','macroPackageType','superPackageType'];
                    $productPackagingDetail = ProductUnitPackageDetail::with($with)->where('product_code',$productVariant->product_code)
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
                $productCode = $warehouseProductDetail->product_code;
                $productVariantCode = $warehouseProductDetail->product_variant_code;

                $productPackagingDetail = ProductUnitPackageDetail::where('product_code',$productCode)
                    ->where('product_variant_code',$productVariantCode)->first();
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

                    $warehouseProductDetail->packaging_info= $packagingInfo;
                }

            }
          return view(Parent::loadViewData($this->module.$this->view.'mass-create-price-setting-of-product'),compact('warehouseProductDetail'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function storeMassPriceSettingOfProduct(WarehouseMassProductPriceSettingRequest $request,$productCode){
        try{
            $validatedData = $request->validated();
            $warehouseCode = getAuthWarehouseCode();


          /*  foreach($validatedData['mrp'] as $key => $mrp){

                $productVariantCode= isset($validatedData['product_variant_code'][$key])?$validatedData['product_variant_code'][$key] : null;

                $productPackagingDetail = ProductUnitPackageDetail::where('product_code',$productCode)
                    ->where('product_variant_code',$productVariantCode)->first();
                if (!$productPackagingDetail){
                    throw new Exception('Product packaging detail not found for product '. $productCode);
                }

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
            }*/


           $warehouseProduct =  $this->warehouseProductService->storeMassPriceSettingOfProduct($validatedData,$warehouseCode,$productCode);

            return $request->session()->flash('success','Product  Price Setting added successfully, Product Name:'.$warehouseProduct->product->product_name.'');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }

    }


}
