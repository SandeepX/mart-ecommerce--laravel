<?php


namespace App\Modules\Vendor\Controllers\Api\Frontend;

use App\Modules\Vendor\Requests\VendorTargetIncentative\VendorTargetIncentativeRequest;
use App\Modules\Vendor\Resources\VendorTargetIncentative\VendorTargetIncentativeResource;
use App\Modules\Vendor\Resources\VendorTargetIncentative\VendorTargetIncentativeListCollection;
use App\Modules\Vendor\Services\VendorTargetIncentiveService;
use App\Modules\Vendor\Helpers\VendorTargetIncentativeHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
Use Exception;

class VendorTargetIncentiveController
{
    private $vendorTargetIncentiveService;

    public function __construct(VendorTargetIncentiveService $vendorTargetIncentiveService)
    {
        $this->vendorTargetIncentiveService = $vendorTargetIncentiveService;

    }

    public function index(Request $request)
    {
        try {
            $VTMCode = $request->get('vendor_target_master_code');
            $product_code = $request->get('product_code');
            $product_variant_code = $request->get('product_variant_code');
            $incentive_type = $request->get('incentive_type');
            $incentive_value_from = $request->get('incentive_value_from');
            $incentive_value_to = $request->get('incentive_value_to');
            $has_meet_target = $request->get('has_meet_target');

            $filterParameters = [
                'VTMCode' => $VTMCode,
                'productCode' => $product_code,
                'ProductVariantCode' => $product_variant_code,
                'incentive_type' => $incentive_type,
                'incentive_value_from' => $incentive_value_from,
                'incentive_value_to' => $incentive_value_to,
                'has_meet_target' => $has_meet_target

            ];

            $allVendorTargetIncentative = VendorTargetIncentativeHelper::filterPaginatedVendorTargetIncentiveDetails($filterParameters, 10);
            return new VendorTargetIncentativeListCollection($allVendorTargetIncentative);

        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), 400);
        }

    }

    public function getVendorTargetIncentativeBycode($VTIcode)
    {
        try {
            $detail = $this->vendorTargetIncentiveService->getVendorTargetIncentativeByVTICode($VTIcode);
            $vendorTargetIncentativeDetails = new VendorTargetIncentativeResource($detail);
            return sendSuccessResponse('Data Found', $vendorTargetIncentativeDetails);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function store(VendorTargetIncentativeRequest $request)
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validated();
            $vendorTargetDetail = $this->vendorTargetIncentiveService->storeVendorTargetIncentiveDetail($validatedData);
            DB::commit();
            return sendSuccessResponse('Vendor Target Incentive created Successfully',$vendorTargetDetail);
        }catch(Exception $exception){
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }


    public function edit($VTIcode)
    {
        try {
            $detail = $this->vendorTargetIncentiveService->getVendorTargetIncentativeByVTICode($VTIcode);
            return sendSuccessResponse('Data Found', $detail);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function update(VendorTargetIncentativeRequest $request,$VTIcode)
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validated();
            $updatedVendorTargetIncentativeDetail = $this->vendorTargetIncentiveService->updateVendorTargetIncentativeDetail($validatedData,$VTIcode);
            DB::commit();
            return sendSuccessResponse('Vendor Target Detail of '.$VTIcode.' updated Successfully',$updatedVendorTargetIncentativeDetail);
        }catch(Exception $exception){
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function destroy($VTIcode)
    {
        DB::beginTransaction();
        try {
            $vendorTargetDetail = $this->vendorTargetIncentiveService->delete($VTIcode);
            DB::commit();
            return sendSuccessResponse('Vendor Target Incentative Detail of '.$VTIcode.' deleted Successfully');
        }catch(Exception $exception){
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }
}
