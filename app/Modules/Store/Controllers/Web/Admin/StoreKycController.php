<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/19/2020
 * Time: 12:52 PM
 */

namespace App\Modules\Store\Controllers\Web\Admin;


use App\Modules\Application\Controllers\BaseController;

use App\Modules\Store\Helpers\FirmKycQueryHelper;
use App\Modules\Store\Helpers\IndividualKycQueryHelper;
use App\Modules\Store\Models\Kyc\FirmKycMaster;
use App\Modules\Store\Models\Kyc\IndividualKYCMaster;
use App\Modules\Store\Requests\Kyc\FirmKycRespondRequest;
use App\Modules\Store\Requests\Kyc\IndividualKycRespondRequest;
use App\Modules\Store\Resources\Kyc\IndividualKycFullResource;
use App\Modules\Store\Services\Kyc\FirmKycService;
use App\Modules\Store\Services\Kyc\IndividualKycService;
use App\Modules\Store\Transformers\FirmKycDetailTransformer;
use App\Modules\Store\Transformers\IndividualKycDetailTransformer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StoreKycController extends BaseController
{

    public $title = 'Stores Kyc';
    public $base_route = 'admin.stores-kyc.';
    public $sub_icon = 'file';
    public $module = 'Store::';


    private $view='admin.kyc.';

    private $individualKycService,$firmKycService;

    public function __construct(IndividualKycService $individualKycService,FirmKycService $firmKycService)
    {
        $this->middleware('permission:View Store Individual Kyc List', ['only' => ['getAllIndividualsKyc']]);
        $this->middleware('permission:Show Store Individual Kyc', ['only' => ['showIndividualKyc']]);
        $this->middleware('permission:Verify Store Individual Kyc', ['only' => ['showIndividualKyc','respondToIndividualKyc']]);

        $this->middleware('permission:View Store Firm Kyc List', ['only' => ['getAllFirmsKyc']]);
        $this->middleware('permission:Show Store Firm Kyc', ['only' => ['showFirmKyc']]);
        $this->middleware('permission:Verify Store Firm Kyc', ['only' => ['showFirmKyc','respondToFirmKyc']]);


        $this->individualKycService = $individualKycService;
        $this->firmKycService = $firmKycService;
    }

    public function getAllIndividualsKyc(Request $request){

        try{
            $filterParameters = [
                'kyc_for' => $request->get('kyc_for'),
                'store_name' => $request->get('store_name'),
                'verification_status' => $request->get('verification_status'),
                'submit_date_from' => $request->get('submit_date_from'),
                'submit_date_to' => $request->get('submit_date_to'),
            ];

            $with =[
                'store','submittedBy'
            ];
            $verificationStatuses=IndividualKYCMaster::VERIFICATION_STATUSES;
            $kycTypes=IndividualKYCMaster::KYC_FOR_TYPES;
            $individualsKyc = IndividualKycQueryHelper::filterPaginatedIndividualKycByParameters($filterParameters,IndividualKYCMaster::RECORDS_PER_PAGE,$with);
            return view(Parent::loadViewData($this->module.$this->view.'individual.index'),
                compact('individualsKyc','verificationStatuses','kycTypes','filterParameters'));

        }catch (Exception $e){
            return redirect()->route('admin.dashboard')->with('danger',$e->getMessage());
        }
    }

    public function showIndividualKyc($kycCode){
        try{
            $individualKyc = $this->individualKycService->findOrFailIndividualKycEagerByCode($kycCode);
            $individualKyc = (new IndividualKycDetailTransformer($individualKyc))->transform();

          //return $individualKyc;
            return view(Parent::loadViewData($this->module.$this->view.'individual.show'),
                compact('individualKyc'));
        }catch (Exception $exception){
            return redirect()->route('admin.dashboard')->with('danger',$exception->getMessage());
        }
    }

    public function respondToIndividualKyc(IndividualKycRespondRequest $request,$kycCode){

        try{
            $validated = $request->validated();
           $this->individualKycService->respondToIndividualKycByAdmin($validated,$kycCode);
            return redirect()->back()->with('success', $this->title .' responded successfully');
        }catch (Exception $exception){

            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    public function getAllFirmsKyc(Request $request){

        try{

            $filterParameters = [
                'business_registered_from' => $request->get('business_registered_from'),
                'store_name' => $request->get('store_name'),
                'verification_status' => $request->get('verification_status'),
                'submit_date_from' => $request->get('submit_date_from'),
                'submit_date_to' => $request->get('submit_date_to'),
            ];

            $with =[
                'store','submittedBy'
            ];

            $verificationStatuses = FirmKycMaster::VERIFICATION_STATUSES;
            $businessRegistrationTypes = FirmKycMaster::BUSINESS_REGISTERED_FROM;
            $firmsKyc = FirmKycQueryHelper::filterPaginatedFirmKycByParameters($filterParameters,FirmKycMaster::RECORDS_PER_PAGE,$with);
            return view(Parent::loadViewData($this->module.$this->view.'firm.index'),
                compact('firmsKyc','filterParameters','verificationStatuses','businessRegistrationTypes'));

        }catch (Exception $e){
            return redirect()->route('admin.dashboard')->with('danger',$e->getMessage());
        }
    }

    public function showFirmKyc($kycCode){

        try{
            $firmKyc = $this->firmKycService->findOrFailFirmKycEagerByCode($kycCode);
            //dd($firmKyc);
            $firmKyc = (new FirmKycDetailTransformer($firmKyc))->transform();

            return view(Parent::loadViewData($this->module.$this->view.'firm.show'),
                compact('firmKyc'));
        }catch (Exception $exception){
            return redirect()->route('admin.dashboard')->with('danger',$exception->getMessage());
        }
    }

    public function respondToFirmKyc(FirmKycRespondRequest $request,$kycCode){

        try{
            $validated = $request->validated();
            $this->firmKycService->respondToFirmKycByAdmin($validated,$kycCode);
            return redirect()->back()->with('success', $this->title .' responded successfully');
        }catch (Exception $exception){

            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }


    public function allowIndividualKycUpdateRequest($kycCode){

        try{
            $this->individualKycService->allowIndividualKycUpdateRequest($kycCode);
            return redirect()->back()->with('success','Kyc Update Request Allowed Successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function allowFirmKycUpdateRequest($kycCode){

        try{
           $this->firmKycService->allowFirmKycUpdateRequest($kycCode);
            return redirect()->back()->with('success','Firm Kyc Update Request Allowed Successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }


}
