<?php


namespace App\Modules\Vendor\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Vendor\Helpers\VendorTargetFilterhelper;
use App\Modules\Vendor\Services\VendorTargetService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class VendorTargetController extends BaseController
{
    public $title = 'Vendor Target';
    public $base_route = 'admin.vendorTarget';
    public $sub_icon = 'home';
    public $module = 'Vendor::';
    private $view='admin.VendorTarget.';

    private $vendorTargetService;

    public function __construct(VendorTargetService $vendorTargetService)
    {
        $this->vendorTargetService  = $vendorTargetService;

    }

    public function index(Request $request)
    {
        $filterParameters = [
            'name' =>$request->get('name'),
            'vendor_name' =>$request->get('vendor_name'),
            'location_name' => $request->get('location_name'),
            'start_date' =>$request->get('start_date'),
            'end_date' =>$request->get('end_date'),
            'is_active' =>$request->get('is_active'),
            'status' =>$request->get('status')
        ];

        try{
            $vendorTargets = VendorTargetFilterhelper::filterPaginatedVendorTarget($filterParameters,10);
            return view(Parent::loadViewData($this->module.$this->view.'index'),compact('vendorTargets','filterParameters'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function changeIsActiveStatus(Request $request)
    {
        DB::beginTransaction();
        try{
            $validatedData = [
                'is_active'=>$request->status,
                'vendorTargetCode' => $request->VTMcode
            ];
            $this->vendorTargetService->updateStatusByVTMCode($validatedData);
            DB::commit();
            request()->session()->flash('success','status changed successfully');
            return response()->json(['success'=>'status changed successfully.']);

        }catch (\Exception $ex){
            DB::rollBack();
            return redirect()->back()->with('danger',$ex->getMessage());
        }
    }

    public function changeVTMStatus(Request $request)
    {
        DB::beginTransaction();
        try{
            $validatedData = [
                'status'=>$request->status,
                'vendorTargetCode' => $request->VTMcode
            ];
            $updateVTMStatus = $this->vendorTargetService->updateVendorTargetStatusByVTMCode($validatedData);
            DB::commit();
            request()->session()->flash('success','Status changed successfully');
            return response()->json(['success'=>'status changed successfully.']);

        }catch (\Exception $ex){
            DB::rollBack();
            return redirect()->back()->with('danger',$ex->getMessage());
        }
    }

    public function showTargetIncentative($VTMcode)
    {
        try{
            $vendorTargetIncentative = $this->vendorTargetService->showVendorTargetIncentative($VTMcode);
            return view(Parent::loadViewData($this->module.$this->view.'vendor-target-incentative-show'),compact('vendorTargetIncentative'));

        }catch (\Exception $ex){
            return redirect()->back()->with('danger',$ex->getMessage());
        }
    }

    public function getAllProvince()
    {
        try{
            return $this->vendorTargetService->getAllProvince();
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function getAllDistrict(Request $request)
    {
        try{
            $provinceCode = $request->provinceCode;
            return $this->vendorTargetService->getAllDistrict($provinceCode);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function getAllMunicipality(Request $request)
    {

        try{
            $districtCode = $request->districtCode;
            return $this->vendorTargetService->getAllMunicipality($districtCode);

        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function getAllWard(Request $request)
    {
        try{
            $municipalityCode = $request->municipalityCode;
            return $this->vendorTargetService->getAllWard($municipalityCode);

        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }


}
