<?php

namespace App\Modules\Store\Controllers\Web\Admin;

use App\Modules\Store\Classes\StoreBalance;
use App\Modules\Store\Events\StoreRegisteredEvent;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\Location\Services\LocationHierarchyService;
use App\Modules\Store\Helpers\StoreFilter;
use App\Modules\Store\Requests\RegistrationChargeRequest;
use App\Modules\Store\Requests\StoreCreateRequest;
use App\Modules\Store\Requests\StoreUpdateRequest;
use App\Modules\Store\Services\StoreService;
use App\Modules\Store\Transformers\StoreDetailTransformer;
use App\Modules\Types\Services\CompanyTypeService;
use App\Modules\Types\Services\RegistrationTypeService;
use App\Modules\Types\Services\StoreSizeService;
use App\Modules\User\Requests\StoreUserCreateTempRequest;
use App\Modules\User\Requests\UserCreateRequest;
use App\Modules\User\Services\UserService;
use App\Modules\Wallet\Helpers\WalletHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Exception;

class StoreController extends BaseController
{

    public $title = 'Store';
    public $base_route = 'admin.stores';
    public $sub_icon = 'file';
    public $module = 'Store::';


    private $view='admin.';

    private $storeService;
    private $companyTypeService;
    private $registrationTypeService;
    private $locationHierarchyService;
    private $userService;
    private $storeSizeService;
    private $storeBalance;



    public function __construct(
        UserService $userService,
        StoreService $storeService,
        CompanyTypeService $companyTypeService,
        RegistrationTypeService $registrationTypeService,
        LocationHierarchyService $locationHierarchyService,
        StoreSizeService $storeSizeService,
        StoreBalance $storeBalance
        )
    {
        $this->middleware('permission:View Store List', ['only' => ['index','getUnapprovedStores']]);
        $this->middleware('permission:Create Store', ['only' => ['create','store']]);
        $this->middleware('permission:Show Store', ['only' => ['show']]);
        $this->middleware('permission:Update Store', ['only' => ['edit','update']]);
        $this->middleware('permission:Delete Store', ['only' => ['destroy']]);
        $this->middleware('permission:Update Store Status', ['only' => ['updateStatus','changeStatus']]);
        $this->middleware('permission:Update Store Purchase Power',['only'=>['togglePurchasePowerStatus']]);

        $this->storeService = $storeService;
        $this->companyTypeService = $companyTypeService;
        $this->registrationTypeService = $registrationTypeService;
        $this->locationHierarchyService = $locationHierarchyService;
        $this->userService = $userService;
        $this->storeSizeService= $storeSizeService;
        $this->storeBalance= $storeBalance;

    }



    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        try{

            $storeName = $request->get('store_name');
            $storeOwner = $request->get('store_owner');
            $registrationType = $request->get('registration_type');
            $companyType = $request->get('company_type');
            $joinedDateFrom = $request->get('joined_date_from');
            $joinedDateTo = $request->get('joined_date_to');
            $province = $request->get('province');
            $district = $request->get('district');
            $municipality = $request->get('municipality');
            $ward = $request->get('ward');
            $storeStatus = $request->get('store_status');
            $storePanVatNo = $request->get('pan_vat_no');
            $storeContactNo = $request->get('store_contact_no');
//            $store

            $filterParameters = [
                'store_name' => $storeName,
                'store_owner' => $storeOwner,
                'registration_type' => $registrationType,
                'company_type' => $companyType,
                'joined_date_from' => $joinedDateFrom,
                'joined_date_to' => $joinedDateTo,
                'province' => $province,
                'district' => $district,
                'municipality' => $municipality,
                'ward' => $ward,
                'store_status' => $storeStatus,
                'store_pan_vat_no' => $storePanVatNo,
                'store_contact_no' => $storeContactNo,
            ];


            $with=[
                'registrationType', 'companyType', 'location','storeCurrentBalance'
            ];
           //dd($filterParameters);
            $stores = StoreFilter::filterPaginatedStores($filterParameters,10,$with);
            $stores->getCollection()->transform(function ($store,$key){
                $store->current_balance = $this->storeBalance->getStoreWalletCurrentBalance($store);
                return $store;
            });


          //  $stores = $this->storeService->getAllStoresWith(['location']);
            $companyTypes = $this->companyTypeService->getAllActiveCompanyTypes();
            $registrationTypes = $this->registrationTypeService->getAllActiveRegistrationTypes();
            $provinces = $this->locationHierarchyService->getAllLocationsByType('province');

             return view(Parent::loadViewData($this->module.$this->view.'index'),compact('stores',
                'filterParameters','companyTypes','registrationTypes','provinces'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }

    }


    /** Stores unapprpoved view**/

    public function getUnapprovedStores(Request $request)
    {
        try{

            $storeName = $request->get('store_name');
            $storeOwner = $request->get('store_owner');
            $registrationType = $request->get('registration_type');
            $companyType = $request->get('company_type');
            $joinedDateFrom = $request->get('joined_date_from');
            $joinedDateTo = $request->get('joined_date_to');
            $province = $request->get('province');
            $district = $request->get('district');
            $municipality = $request->get('municipality');
            $ward = $request->get('ward');
            $storeStatus = $request->get('store_status');
            $storePanVatNo = $request->get('pan_vat_no');
            $storeContactNo = $request->get('store_contact_no');

            $filterParameters = [
                'store_name' => $storeName,
                'store_owner' => $storeOwner,
                'registration_type' => $registrationType,
                'company_type' => $companyType,
                'joined_date_from' => $joinedDateFrom,
                'joined_date_to' => $joinedDateTo,
                'province' => $province,
                'district' => $district,
                'municipality' => $municipality,
                'ward' => $ward,
                'store_status' => $storeStatus,
                'store_pan_vat_no' => $storePanVatNo,
                'store_contact_no' => $storeContactNo,
            ];

           // dd($filterParameters);

            $with=[
                'registrationType', 'companyType', 'location','storeCurrentBalance'
            ];
            //dd($filterParameters);
            $stores = StoreFilter::filterPaginatedPendingRegistrationStores($filterParameters,10,$with);
            $stores->getCollection()->transform(function ($store,$key){
                $store->current_balance = $this->storeBalance->getStoreWalletCurrentBalance($store);
                return $store;
            });

            //  $stores = $this->storeService->getAllStoresWith(['location']);
            $companyTypes = $this->companyTypeService->getAllActiveCompanyTypes();
            $registrationTypes = $this->registrationTypeService->getAllActiveRegistrationTypes();
            $provinces = $this->locationHierarchyService->getAllLocationsByType('province');

            return view(Parent::loadViewData($this->module.$this->view.'index-store-registration'),compact('stores',
                'filterParameters','companyTypes','registrationTypes','provinces'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }

    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
        $companyTypes = $this->companyTypeService->getAllActiveCompanyTypes();
        $registrationTypes = $this->registrationTypeService->getAllActiveRegistrationTypes();
        $provinces = $this->locationHierarchyService->getAllLocationsByType('province');
        $storeSizes = $this->storeSizeService->getAllActiveStoreSizes();

        if ($request->ajax()) {
            return view(Parent::loadViewData($this->module.$this->view.'common.form'),compact('companyTypes',
                'registrationTypes', 'provinces','storeSizes'));
        }

        return view(Parent::loadViewData($this->module.$this->view.'create'),compact('companyTypes',
            'registrationTypes', 'provinces','storeSizes'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(StoreCreateRequest $storeRequest, StoreUserCreateTempRequest $userRequest)
    {
       try{
           $validatedStore = $storeRequest->validated();
           $validatedUser = $userRequest->validated();

          $storeWithUser = $this->storeService->createStoreWithUser($validatedUser,$validatedStore);
       }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
       }

       return sendSuccessResponse($this->title . ': '. $storeWithUser['store']->store_name .' created successfully');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($storeCode)
    {
        $store = $this->storeService->findOrFailStoreByCode($storeCode);
        $store = (new StoreDetailTransformer($store))->transform();

        return view(Parent::loadViewData($this->module.$this->view.'show'),compact('store'));

    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($storeCode)
    {
        try{
            $store = $this->storeService->findOrFailStoreByCode($storeCode);
            $companyTypes = $this->companyTypeService->getAllCompanyTypes();
            $registrationTypes = $this->registrationTypeService->getAllRegistrationTypes();
            $provinces = $this->locationHierarchyService->getAllLocationsByType('province');

            $storeSizes = $this->storeSizeService->getAllActiveStoreSizes();

            $locationPath = $this->locationHierarchyService->getLocationPath(
                $store->store_location_code,
                ['ward.municipality.district.province.country']
            );
        }catch (\Exception $ex){
            return redirect()->back()->with('danger',$ex->getMessage());
        }
        return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('store', 'companyTypes', 'registrationTypes', 'provinces', 'locationPath','storeSizes'));

    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(StoreUpdateRequest $storeRequest, $storeCode)
    {
        $validatedStore = $storeRequest->validated();
        try{
            $store = $this->storeService->updateStore($validatedStore, $storeCode);

        }catch(\Exception $exception){

            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
        return redirect()->back()->with('success', $this->title . ': '. $store->store_name .' Updated Successfully')->withInput();

    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($storeCode)
    {
        try{
            $store = $this->storeService->deleteStore($storeCode);
            return redirect()->back()->with('success', $this->title . ': '. $store->store_name .' Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function updateStatus(Request $request,$storeCode)
    {
        try{
            $validatedData['remarks'] = $request->remarks;
            $validatedData['store_status'] = $request->store_status;
            DB::beginTransaction();
            $store = $this->storeService->updateStatus($storeCode,$validatedData);
            DB::commit();
            return redirect()->back()->with('success', 'status updated successfully');
        }catch (Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function togglePurchasePowerStatus($storeCode){
         try{
             DB::beginTransaction();
               $this->storeService->togglePurchasePowerStatus($storeCode);
             DB::commit();
             return redirect()->back()->with('success', 'Store Purchase Power Status changed successfully');
         }catch (Exception $exception){
             DB::rollBack();
             return redirect()->back()->with('danger', $exception->getMessage());
         }
    }

    public function changeStatus($storeCode,$status){
        try{
            $updateStatus = $this->storeService->changeStoreStatus($storeCode,$status);
            return redirect()->back()->with('success','Store :'.$updateStatus->store_name.' status changed successfully');
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}

