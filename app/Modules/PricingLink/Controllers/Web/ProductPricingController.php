<?php


namespace App\Modules\PricingLink\Controllers\Web;


use App\Modules\AlpasalWarehouse\Services\WarehouseService;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\Location\Services\LocationHierarchyService;
use App\Modules\OTP\Services\OTPService;
use App\Modules\PricingLink\Helper\ProductPricingHelper;
use App\Modules\PricingLink\Models\PricingMaster;
use App\Modules\PricingLink\Requests\StoreInfoForOtpRequest;
use App\Modules\PricingLink\Requests\VerifyOtpRequest;
use App\Modules\PricingLink\Services\PricingMasterService;
use App\Modules\PricingLink\Services\ProductPricingService;
use App\Modules\Product\Helpers\ProductUnitPackagingHelper;
use App\Modules\Product\Models\ProductUnitPackageDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class ProductPricingController extends BaseController
{
    public $title = 'Product Pricing';
    public $base_route = 'product-pricing';
    public $sub_icon = 'file';
    public $module = 'PricingLink::';
    public $view = 'front.';

    private $warehouseService,$productPricingService,$locationHierarchyService,$otpService,$pricingMasterService;

    public function __construct(WarehouseService $warehouseService,
                                ProductPricingService $productPricingService,
                                LocationHierarchyService $locationHierarchyService,
                                OTPService $otpService,
                                PricingMasterService $pricingMasterService)
    {

        $this->warehouseService = $warehouseService;
        $this->productPricingService = $productPricingService;
        $this->locationHierarchyService = $locationHierarchyService;
        $this->otpService = $otpService;
        $this->pricingMasterService = $pricingMasterService;
    }

    public function exceptionPage()
    {
        return view(Parent::loadViewData($this->module . $this->view . 'exception-page'));
    }


    public function index($link,Request $request)
    {
        try{
            $filterParameters = [
                'product' =>$request->get('product')
            ];

            if(isset($link))
            {
                $pricingLink = $this->productPricingService->findPricingLinkByLinkCode($link);

                if(empty($pricingLink))
                {
                    throw new exception('Link is invalid');
                }

                $startTime = strtotime(now('Asia/kathmandu')->toDateTimeString());
                $endTime = strtotime($pricingLink->expires_at);

                if ($startTime>$endTime) {
                    throw new Exception('The given link is already expired !');
                }

                $warehouseProducts = ProductPricingHelper::getWarehouseWiseProducts($pricingLink->warehouse_code,$filterParameters);

                if(!$request->ajax()){
                    $products = ProductPricingHelper::getWarehouseAllProductsForFilter($pricingLink->warehouse_code);
                }

                foreach($warehouseProducts as $key=>$product)
                {
                    $packageTypes =[];
                    $productPackagingDetail = ProductPricingHelper::getProductPackageTypes($product->product_code,$product->product_variant_code);

                    if ($productPackagingDetail && $product->warehouseProductPriceMaster){
                        //throw new Exception('Product packaging details not found for product '. $product->product_code);
                        if($productPackagingDetail->micro_unit_code){
                            array_push($packageTypes,[
                                'package_code'=>$productPackagingDetail->micro_unit_code,
                                'package_name'=>$productPackagingDetail->micro_unit_name,
                                'mrp'=>$product->warehouseProductPriceMaster->mrp,
                            ]);
                        }
                        if ($productPackagingDetail->unit_code){
                            array_push($packageTypes,[
                                'package_code'=>$productPackagingDetail->unit_code,
                                'package_name'=>$productPackagingDetail->unit_name,
                                'mrp'=>$product->warehouseProductPriceMaster->mrp *$productPackagingDetail->micro_to_unit_value,
                            ]);
                        }
                        if ($productPackagingDetail->macro_unit_code){
                            array_push($packageTypes,[
                                'package_code'=>$productPackagingDetail->macro_unit_code,
                                'package_name'=>$productPackagingDetail->macro_unit_name,
                                'mrp'=>$product->warehouseProductPriceMaster->mrp *($productPackagingDetail->unit_to_macro_value *$productPackagingDetail->micro_to_unit_value),
                            ]);
                        }
                        if ($productPackagingDetail->super_unit_code){
                            $microValue=$productPackagingDetail->macro_to_super_value * $productPackagingDetail->unit_to_macro_value *$productPackagingDetail->micro_to_unit_value;
                            array_push($packageTypes,[
                                'package_code'=>$productPackagingDetail->super_unit_code,
                                'package_name'=>$productPackagingDetail->super_unit_name,
                                'mrp'=>$product->warehouseProductPriceMaster->mrp *$microValue,
                            ]);
                        }
                    }
                    $warehouseProducts[$key]['package_details'] = collect($packageTypes)->map(function($product){
                        return $product;
                    });
                }

                $groupedByProductCode = $warehouseProducts->mapToGroups(function ($product,$key) {
                    return [
                        $product['product_code'] => $product
                    ];
                })->values();

                //preserving pagination
                $finalProducts = $warehouseProducts->setCollection($groupedByProductCode);

                if($request->ajax()){
                    $response = [];
                    $response['html'] = view( Parent::loadViewData($this->module . $this->view . 'pricing-link-table'),
                        compact('finalProducts'))->render();
                    return response()->json($response);
                }

                return view(Parent::loadViewData($this->module . $this->view . 'index'),compact('pricingLink',
                        'link',
                        'finalProducts',
                        'products',
                        'filterParameters')
                );
            }
            else{
                return view(Parent::loadViewData($this->module . $this->view . 'index'));
            }

        }catch(Exception $exception){
            Session::flash('danger',$exception->getMessage());
            if($request->ajax()){
                return sendErrorResponse($exception->getMessage(),$exception->getCode());
            }
            return redirect()->route('product-pricing.exception')->with('danger', $exception->getMessage());
        }

        //


    }

    public function form(Request $request,$link)
    {
        try {
            $pricingLink = $this->productPricingService->findPricingLinkByLinkCode($link);

            if (empty($pricingLink)) {
                throw new exception('Link is invalid');
            }

            $startTime = strtotime(now('Asia/kathmandu')->toDateTimeString());
            $endTime = strtotime($pricingLink->expires_at);

            if ($startTime>$endTime) {
                throw new Exception('The given link is already expired !');
            }

            $provinces = $this->locationHierarchyService->getAllLocationsByType('province');

            if ($request->ajax()) {
                return view(Parent::loadViewData($this->module . $this->view . 'form'),
                    compact('provinces', 'pricingLink'));
            }

            return view(Parent::loadViewData($this->module . $this->view . 'form'),
                compact('provinces', 'pricingLink'));
        }catch(Exception $exception){
            Session::flash('danger',$exception->getMessage());
            return redirect()->route('product-pricing.exception')->with('danger', $exception->getMessage());
        }
    }

    public function store(StoreInfoForOtpRequest $request)
    {
        try{
            DB::beginTransaction();
            $validatedData = $request->validated();
            $pricingLinkDetail = $this->pricingMasterService->findPricingLinkByCode($validatedData['pricing_master_code']);

            $pricingView = $this->productPricingService->storePricingView($validatedData);
            DB::commit();

            $data = Session::get('pricing_view_session');
            if($data)
            {
                $pricingLink = $this->pricingMasterService->findPricingLinkByCode($data['pricing_master_code']);
                return redirect($pricingLink->link)->with('success','Your OTP is already verified ! ');
            }
            else{

                return redirect()->route('product-pricing.otpVerifyForm',
                        [$pricingLinkDetail['link_code'],$validatedData['mobile_number']
                    ])

                    ->with('success','mobile info stored successfully !Please verify Otp');
            }

        }catch(Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('danger',$exception->getMessage());
        }

    }

    public function otpVerifyForm($linkCode,$mobileNumber)
    {
        return view(Parent::loadViewData($this->module.$this->view.'otp-verify-form'),
            compact('linkCode',
        'mobileNumber'
        ));
    }

    public function verifyOTPWithoutAuth(VerifyOtpRequest $request)
    {

        try{
            $validatedData = $request->validated();
            $pricingLinkDetail = $this->productPricingService->findPricingLinkByLinkCode($validatedData['link_code']);
            $validatedData['pricing_master_code'] = $pricingLinkDetail->pricing_master_code;

            $this->otpService->verifyOTPWithoutAuth($validatedData);
            $data = Session::get('pricing_view_session');
            $pricingLink = $this->pricingMasterService->findPricingLinkByCode($data['pricing_master_code']);

            return redirect($pricingLink->link)->with('success','Congratulations!!! Your OTP verified Successfully ');
        }catch(\Exception $e){
            return redirect()->back()->with('danger',$e->getMessage());
        }
    }
}
