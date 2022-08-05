<?php

namespace App\Modules\Vendor\Controllers\Web\Admin;

use App\Modules\Vendor\Events\VendorRegisteredEvent;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\Location\Services\LocationHierarchyService;
use App\Modules\Types\Services\CompanyTypeService;
use App\Modules\Types\Services\RegistrationTypeService;
use App\Modules\Types\Services\VendorTypeService;
use App\Modules\User\Mails\WelcomeEmail;
use App\Modules\User\Requests\VendorUserCreateRequest;
use App\Modules\User\Services\UserService;
use App\Modules\User\Jobs\SendWelcomeEmailJob;
use App\Modules\Vendor\Helpers\VendorFilter;
use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Requests\VendorCreateRequest;
use App\Modules\Vendor\Requests\VendorUpdateRequest;
use App\Modules\Vendor\Services\VendorService;
use App\Modules\Vendor\Transformers\VendorDetailTransformer;
use App\Modules\Wallet\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Exception;
class VendorController extends BaseController
{

    public $title = 'Vendor';
    public $base_route = 'admin.vendors';
    public $sub_icon = 'file';
    public $module = 'Vendor::';


    private $view;

    private $userService;
    private $vendorService;
    private $vendorTypeService;
    private $companyTypeService;
    private $registrationTypeService;
    private $locationHierarchyService;
    private $walletService;



    public function __construct(
        UserService $userService,
        VendorService $vendorService,
        VendorTypeService $vendorTypeService,
        CompanyTypeService $companyTypeService,
        RegistrationTypeService $registrationTypeService,
        LocationHierarchyService $locationHierarchyService,
        WalletService $walletService
        )
    {
        $this->middleware('permission:View Vendor List', ['only' => ['index']]);
        $this->middleware('permission:Create Vendor', ['only' => ['create','store']]);
        $this->middleware('permission:Show Vendor', ['only' => ['show']]);
        $this->middleware('permission:Update Vendor', ['only' => ['edit','update']]);
        $this->middleware('permission:Delete Vendor', ['only' => ['destroy']]);
        $this->middleware('permission:Update Vendor Status', ['only' => ['changeStatus']]);

        $this->view = 'admin.';
        $this->userService = $userService;
        $this->vendorService = $vendorService;
        $this->vendorTypeService = $vendorTypeService;
        $this->companyTypeService = $companyTypeService;
        $this->registrationTypeService = $registrationTypeService;
        $this->locationHierarchyService = $locationHierarchyService;
        $this->walletService = $walletService;

    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        try{
            $filterParameters = [
                'vendor_name' => $request->get('vendor_name'),
                'vendor_owner' => $request->get('vendor_owner'),
                'company_type' => $request->get('company_type'),
                'joined_date_from' => $request->get('joined_date_from'),
                'joined_date_to' => $request->get('joined_date_to'),
                'province' => $request->get('province'),
                'district' => $request->get('district'),
                'municipality' => $request->get('municipality'),
                'ward' => $request->get('ward'),
            ];
            $with=[
                'companyType', 'location'
            ];

            $vendors= VendorFilter::filterPaginatedVendors($filterParameters,Vendor::VENDOR_PER_PAGE,$with);
            /*$vendors = $this->vendorService->getAllVendors([
                'companyType','registrationType','vendorType'
            ]);*/
            $companyTypes = $this->companyTypeService->getAllActiveCompanyTypes();
            $provinces = $this->locationHierarchyService->getAllLocationsByType('province');
            return view(Parent::loadViewData($this->module.$this->view.'index'),compact('vendors',
                'filterParameters','companyTypes','provinces'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }

    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $vendorTypes = $this->vendorTypeService->getAllVendorTypes();
        $companyTypes = $this->companyTypeService->getAllCompanyTypes();
        $registrationTypes = $this->registrationTypeService->getAllRegistrationTypes();
        $provinces = $this->locationHierarchyService->getAllLocationsByType('province');
        return view(Parent::loadViewData($this->module.$this->view.'create'),compact('vendorTypes', 'companyTypes', 'registrationTypes', 'provinces'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(VendorCreateRequest $request, VendorUserCreateRequest $userRequest)
    {
       // return sendSuccessResponse(' Created Successfully');
        $validatedUser = $userRequest->validated();
        $validatedVendorDetails = $request->validated();
        $validatedWalletData = [];
       try{
           DB::beginTransaction();

           // Creating User Account For Vendor
         $validatedUser['password'] = uniqueHash();
        //  $validatedUser['password'] = $validatedUser['login_phone'];
            $user = $this->userService->storeVendorUser($validatedUser);

            //Storing Vendor Details
            $validatedVendorDetails['user_code'] = $user->user_code;
            $vendor = $this->vendorService->storeVendorDetails($validatedVendorDetails);

            event(new VendorRegisteredEvent($vendor));

            // dispatching welcome mail
            $data = [
                 'user' => $user,
                 'login_password' => $validatedUser['password'],
                 'user_type' => 'vendor',
                 'login_link' => config('site_urls.ecommerce_site')."/user-login"
            ];
            SendWelcomeEmailJob::dispatch($user,new WelcomeEmail($data));

            DB::commit();
       }catch(\Exception $exception){
             DB::rollback();
            return sendErrorResponse($exception->getMessage().$exception->getFile(), 400);
       }

       return sendSuccessResponse($this->title . ': '. $vendor->vendor_name .' Created Successfully');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($vendorCode)
    {
        $vendor = $this->vendorService->findOrFailVendorByCode($vendorCode);
        $vendor = (new VendorDetailTransformer($vendor))->transform();
        return view(Parent::loadViewData($this->module.$this->view.'show'),compact('vendor'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($vendorCode)
    {
        try{
            $vendor = $this->vendorService->findOrFailVendorByCode($vendorCode);
            $vendorTypes = $this->vendorTypeService->getAllVendorTypes();
            $companyTypes = $this->companyTypeService->getAllCompanyTypes();
            $registrationTypes = $this->registrationTypeService->getAllRegistrationTypes();
            $provinces = $this->locationHierarchyService->getAllLocationsByType('province');

            $locationPath = $this->locationHierarchyService->getLocationPath(
                $vendor->vendor_location_code,
                ['ward.municipality.district.province']
            );


        }catch (\Exception $ex){
            return redirect()->back()->with('danger',$ex->getMessage());
        }
        return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('vendor', 'vendorTypes', 'companyTypes', 'registrationTypes', 'provinces', 'locationPath'));

    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(VendorUpdateRequest $request, $vendorCode)
    {
        $validated = $request->validated();
        try{
            $vendor = $this->vendorService->updateVendorDetails($validated, $vendorCode);

        }catch(\Exception $exception){

            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
        return redirect()->back()->with('success', $this->title . ': '. $vendor->vendor_name .' Updated Successfully')->withInput();

    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($vendorCode)
    {
        try{
            throw new Exception('Vendor Can not be deleted');
            $vendor = $this->vendorService->deleteVendorDetails($vendorCode);
            return redirect()->back()->with('success', $this->title . ': '. $vendor->vendor_name .' Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function changeStatus($vendorCode,$status){
        try{
            $updateStatus = $this->vendorService->changeVendorStatus($vendorCode,$status);
            return redirect()->back()->with('success','Store :'.$updateStatus->vendor_name.' status changed successfully');
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
