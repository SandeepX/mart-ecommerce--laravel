<?php


namespace App\Modules\Vendor\Controllers\Api\Frontend\SalesOrder;


use App\Http\Controllers\Controller;

use App\Modules\Vendor\Helpers\VendorSalesOrderReturnFilter;
use App\Modules\Vendor\Helpers\VendorSalesReportHelper;
use App\Modules\Vendor\Requests\VendorSalesReturnRespondRequest;
use App\Modules\Vendor\Resources\VendorOrder\VendorSalesReturnCollection;
use App\Modules\Vendor\Resources\VendorOrder\VendorSalesReturnDetailCollection;
use App\Modules\Vendor\Services\VendorSalesReturnService;
use Exception;
use Illuminate\Http\Request;
;
use Illuminate\Validation\Rule;

class VendorSalesOrderController extends Controller
{
    private $vendorSalesReturnService;

    public function __construct(VendorSalesReturnService $vendorSalesReturnService)
    {
        $this->vendorSalesReturnService= $vendorSalesReturnService;
    }

    public function getSalesReturns(){
        try{
            $filterParameters=[
                'vendor_code'=>getAuthVendorCode()
            ];
            $with=[];
            $salesReturns = VendorSalesOrderReturnFilter::filterPaginatedGroupedVendorSalesOrderReturn($filterParameters,10,$with);
            //return  $salesReturns;
            return  new VendorSalesReturnCollection($salesReturns);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function showSalesReturnDetail($warehousePurchaseOrderCode){

        try{
            $salesReturnDetail =$this->vendorSalesReturnService->getAuthVendorSalesReturnDetail($warehousePurchaseOrderCode);
           // return  $salesReturnDetail;
            return  new VendorSalesReturnDetailCollection($salesReturnDetail);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function respondToSalesReturn(VendorSalesReturnRespondRequest $request,$warehousePurchaseReturnCode){
        try{
           $validatedData = $request->validated();
           $this->vendorSalesReturnService->respondToSalesReturnByVendor($validatedData,$warehousePurchaseReturnCode);
            return sendSuccessResponse('Sales return responded successfully');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }
    public function getVendorSalesReportByVendorCode(Request $request){
        $validateMonth=$request->validate([
            'months'=>['required','integer','max:12']
        ]);
        $vendorCode=getAuthVendorCode();
        $salesReport= VendorSalesReportHelper::getVendorSalesReportByVendorCode($vendorCode,$validateMonth);
        return $salesReport;
    }
}
