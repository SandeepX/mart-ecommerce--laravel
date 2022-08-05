<?php


namespace App\Modules\Vendor\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Modules\ActivityLog\Helpers\LogActivity;
use App\Modules\ActivityLog\Jobs\LogActivityJob;
use App\Modules\Vendor\Resources\VendorTargetMaster\VendorTargetMasterResource;
use App\Modules\Vendor\Resources\VendorTargetMaster\VendorTargetListCollection;
use App\Modules\Vendor\Requests\VendorTargetSetStoreRequest;
use App\Modules\Vendor\Requests\VendorTargetSetUpdateRequest;
use App\Modules\Vendor\Helpers\VendorSetTargetHelper;
Use App\Modules\Vendor\Services\VendorTargetService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class VendorTargetMasterController extends Controller
{
    private $vendorTargetService;

    public function __construct(VendorTargetService $vendorTargetService)
    {
        $this->vendorTargetService = $vendorTargetService;

    }

    public function index(Request $request)
    {
        try {
            $vendor_name = $request->get('name');
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            $isActive = $request->get('is_active');
            $status = $request->get('status');

            $filterParameters = [
                'vendor_code' => getAuthVendorCode(),
                'vendor_name' => $vendor_name,
                'startDate_from' => $startDate,
                'endDate_to' => $endDate,
                'is_active' => $isActive,
                'status' => $status
            ];

            $allVendorTarget = VendorSetTargetHelper::filterPaginatedVendorTargetDetails($filterParameters, 10);
            if(!is_null($allVendorTarget)){
                $data = new VendorTargetListCollection($allVendorTarget);
            }else{
                $data = null;
            }
            return sendSuccessResponse('Data Found', $data);

        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), 400);
        }

    }

    public function store(VendorTargetSetStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validated();
            $vendorTargetDetail = $this->vendorTargetService->storeVendorTargetMasterDetail($validatedData);
            DB::commit();
            return sendSuccessResponse('Vendor Target Set Successfully',$vendorTargetDetail);
        }catch(Exception $exception){
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function getVendorTargetByCode($VTMcode)
    {
        try {
            $detail = $this->vendorTargetService->getVendorTargetByVTMCode($VTMcode);

            $vendorTargetDetail = new VendorTargetMasterResource($detail);
            return sendSuccessResponse('Data Found', $vendorTargetDetail);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function edit($VTMcode)
    {
        try {
            $detail = $this->vendorTargetService->getVendorTargetByVTMCode($VTMcode);
            return sendSuccessResponse('Data Found', $detail);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function update(VendorTargetSetUpdateRequest $request, $VTMcode)
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validated();
            $updatedVendorTargetDetail = $this->vendorTargetService->updateVendorTargetDetail($validatedData,$VTMcode);
            DB::commit();
            return sendSuccessResponse('Vendor Target Detail of '.$VTMcode.' updated Successfully',$updatedVendorTargetDetail);
        }catch(Exception $exception){
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function destroy($VTMCode)
    {
        DB::beginTransaction();
        try {
            $vendorTargetDetail = $this->vendorTargetService->delete($VTMCode);
            DB::commit();
            return sendSuccessResponse('Vendor Target Detail of '.$VTMCode.' deleted Successfully');
        }catch(Exception $exception){
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }


}
