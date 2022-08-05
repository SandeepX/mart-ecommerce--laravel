<?php


namespace App\Modules\SalesManager\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\SalesManager\Services\AssignStoreService;
use App\Modules\SalesManager\Requests\AssignStore\AssignStoreRequest;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;


class AssignStoreController extends BaseController
{
    public $title = 'Manager with Store';
    public $base_route = 'admin.assignStore';
    public $sub_icon = 'home';
    public $module = 'SalesManager::';
    private $view = 'admin.assign-store.';

    private $assignStoreService;

    public function __construct(AssignStoreService $assignStoreService)
    {
        $this->middleware('permission:Assign Stores To Manager', ['only' => ['assignManagerWithStore']]);
        $this->middleware('permission:View All Assigned Store', ['only' => ['getAllAssignedStoreByManagerCode']]);
        $this->middleware('permission:Unlink Store From Manager', ['only' => ['unlinkStoreFromManager']]);

        $this->assignStoreService = $assignStoreService;
    }



    public function assignManagerWithStore(AssignStoreRequest $request)
    {
        DB::beginTransaction();
           try{
               $validated = $request->validated();
               $this->assignStoreService->assignManagerWithStore($validated);
               DB::commit();
               return redirect()->back()->with('success','Manager Assigned to store Successfully');

           }catch(Exception $e){
               DB::rollBack();
               return redirect()->back()->with('danger',$e->getMessage());
           }
    }

    public function getAllAssignedStoreByManagerCode($managerCode)
    {
        try{
            $storeDetail = $this->assignStoreService->getAllAssignedStoreByManagerCode($managerCode);
            return view(parent::loadViewData($this->module.$this->view.'assigned-store-show'),compact('storeDetail'));
        }catch(Exception $e){
            return redirect()->back()->with('danger',$e->getMessage());
        }

    }

    public function unlinkStoreFromManager($managerStoreCode)
    {
        DB::beginTransaction();
        try{
            $this->assignStoreService->deleteAssignedStore($managerStoreCode);
            DB::commit();
            return redirect()->back()->with('success','Manager Assigned to store deleted');

        }catch(Exception $e){
            DB::rollBack();
            return redirect()->back()->with('danger',$e->getMessage());
        }
    }


}

