<?php

namespace App\Modules\SalesManager\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Location\Services\LocationHierarchyService;
use App\Modules\SalesManager\Helpers\SalesManagerFilter;
use App\Modules\SalesManager\Models\Manager;
use App\Modules\SalesManager\Models\SalesManagerRegistrationStatus;
use App\Modules\SalesManager\Requests\SalesManagerPasswordUpdateRequest;
use App\Modules\SalesManager\Services\SalesManagerService;
use App\Modules\SalesManager\Services\AssignStoreService;
use App\Modules\SalesManager\Services\SalesManagerRegistrationStatusService;
use App\Modules\User\Repositories\UserRepository;
use App\Modules\User\Requests\UpdateStatusAndAssignAreaRequest;
use App\Modules\User\Services\UserService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class SalesManagerController extends BaseController
{

    public $title = 'Sales Manager';
    public $base_route = 'admin.salesmanager';
    public $sub_icon = 'file';
    public $module = 'SalesManager::';
    private $view = 'admin.';

    private $salesManagerService;
    private $salesManagerRegistrationService;
    private $assignStoreService;
    private $locationHierarchyService;

    public function __construct(
        SalesManagerService $salesManagerService,
        SalesManagerRegistrationStatusService $salesManagerRegistrationService,
        AssignStoreService $assignStoreService,
        LocationHierarchyService $locationHierarchyService
    )
    {

        $this->middleware('permission:View Manager Lists', ['only' => ['index']]);
        $this->middleware('permission:Show Manager', ['only' => ['show']]);
        $this->middleware('permission:Change Manager Status', ['only' => ['changeStatus']]);
        $this->middleware('permission:Assign Stores To Manager', ['only' => ['assignStore']]);
        $this->middleware('permission:View All Referred Store', ['only' => ['getAllReferredStoreByManagerCode']]);
        $this->middleware('permission:Update Manager Password', ['only' => ['showChangePassword','updatePassword']]);

        $this->salesManagerService = $salesManagerService;
        $this->salesManagerRegistrationService = $salesManagerRegistrationService;
        $this->assignStoreService = $assignStoreService;
        $this->locationHierarchyService = $locationHierarchyService;

    }

    public function index(Request $request)
    {
        try {

            $filterParameters = [
                'user_type' => ['sales-manager'],
                'name' => $request->get('name'),
                'status' => $request->get('status'),
                'province' => $request->get('province'),
                'district' => $request->get('district'),
                'municipality' => $request->get('municipality'),
                'ward' => $request->get('ward'),
                'temporary_province' => $request->get('temporary_province'),
                'temporary_district' => $request->get('temporary_district'),
                'temporary_municipality' => $request->get('temporary_municipality'),
                'temporary_ward' => $request->get('temporary_ward'),
            ];

            $sales_managers = SalesManagerFilter::filterPaginatedSalesManager($filterParameters, 10);
            $status = Manager::STATUS;
            $provinces = $this->locationHierarchyService->getAllLocationsByType('province');


            return view(Parent::loadViewData($this->module . $this->view . 'index'),
                compact('sales_managers',
                    'filterParameters',
                                        'status',
                                       'provinces'
                ));
        } catch (Exception $exception) {
            echo $exception->getMessage();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function show($managerCode)
    {
        try {
            $salesManager = $this->salesManagerService->findOrFailSalesManagerByCodeWith($managerCode, ['managerDocs','user']);
            $managerDocs = $salesManager->managerDocs;
        } catch (\Exception $ex) {
            return redirect()->back()->with('danger', $ex->getMessage());
        }

        return view(Parent::loadViewData($this->module . $this->view . 'show'),
            compact('salesManager', 'managerDocs'));

    }


    public function changeStatus(UpdateStatusAndAssignAreaRequest $request, $managerCode)
    {
        try {
            $validatedData = $request->validated();
            $userRegistrationStatus = $this->salesManagerService->updateStatusByUserCode($validatedData, $managerCode);
            return redirect()->back()->with('success', 'User registration Updated Successfully');
        } catch (\Exception $ex) {
            return redirect()->back()->with('danger', $ex->getMessage());
        }
    }

    public function assignStore($managerCode)
    {
        try {
            $getAllStore = $this->salesManagerService->getAllStore();
            $storeDetail = $this->assignStoreService->getAllAssignedStoreByManagerCode($managerCode);
            return view(Parent::loadViewData($this->module . $this->view . 'assign-store'), compact('managerCode', 'getAllStore', 'storeDetail'));
        } catch (Exception $e) {
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }

    public function getAllReferredStoreByManagerCode($managerCode)
    {
        try {
            $manager = $this->salesManagerService->findOrFailSalesManagerByCodeWith($managerCode);
            $referredStores = $this->salesManagerService->getStoreByReferralCode($managerCode);
            return view(Parent::loadViewData($this->module . $this->view . 'referred-store'),
                compact('referredStores','manager'));
        } catch (Exception $e) {
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }

    public function getAllReferredManagerByManagerCode($managerCode)
    {
        try {
            $manager = $this->salesManagerService->findOrFailSalesManagerByCodeWith($managerCode);
            $referredManagers = $this->salesManagerService->getReferedManagersByReferralCode($managerCode);
            return view(Parent::loadViewData($this->module . $this->view . 'referred-manager'),
                compact('referredManagers','manager'));
        } catch (Exception $e) {
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }

    public function showChangePassword($managerUserCode)
    {
        try{
            return view(Parent::loadViewData($this->module . $this->view . 'change-password'),
                compact('managerUserCode'));
        }catch(Exception $e){
            return redirect()->back()->with('danger',$e->getMessage());
        }
    }

    public function updatePassword(SalesManagerPasswordUpdateRequest $request,$managerUserCode)
    {
        try{
            $validated = $request->validated();

            $userRepo = new UserRepository();
            $user= $userRepo->findOrFailUserByCode($managerUserCode);

            DB::beginTransaction();
            $userRepo->updateUserPassword($user,$validated['password']);

            DB::commit();
            return redirect()->back()->with('success', $this->title . ': '. $user->name .' password updated successfully');
        }catch(Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

}

