<?php


namespace App\Modules\SupportAdmin\Controllers\stores;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Store\Helpers\FirmKycQueryHelper;
use App\Modules\Store\Helpers\IndividualKycQueryHelper;
use App\Modules\Store\Helpers\StoreKycListingHelper;
use App\Modules\Store\Models\Kyc\FirmKycMaster;
use App\Modules\Store\Models\Kyc\IndividualKYCMaster;
use App\Modules\Store\Services\Kyc\FirmKycService;
use App\Modules\Store\Services\Kyc\IndividualKycService;
use App\Modules\Store\Services\StoreService;
use App\Modules\Store\Transformers\FirmKycDetailTransformer;
use App\Modules\Store\Transformers\IndividualKycDetailTransformer;
use Illuminate\Http\Request;
use Exception;

class StoreKycDetailForSupportAdminController extends BaseController
{
    public $title = 'Store Detail For Admin Support';
    public $base_route = 'support-admin.';
    public $sub_icon = 'file';
    public $module = 'SupportAdmin::';

    private $view = 'stores.';

    public $individualKycService;
    public $storeService;
    public $firmKycService;


    public function __construct(StoreService $storeService,
                                IndividualKycService $individualKycService,
                                FirmKycService $firmKycService
    )
    {
       $this->individualKycService = $individualKycService;
       $this->storeService = $storeService;
       $this->firmKycService = $firmKycService;
    }

    public function getIndividualKycDetail($storeCode,Request $request)
    {
        try{
            $store = $this->storeService->findStoreByCode($storeCode);

            $response =[];

            $filterParameters = [
                'kyc_for' => $request->get('kyc_for'),
                'store_name' => $store->store_name,
                'verification_status' => null,
                'submit_date_from' => null,
                'submit_date_to' => null,
            ];
            $with =[
                'store','submittedBy'
            ];
            $verificationStatuses=IndividualKYCMaster::VERIFICATION_STATUSES;
            $kycTypes=IndividualKYCMaster::KYC_FOR_TYPES;
            $individualsKyc = IndividualKycQueryHelper::filterPaginatedIndividualKycByParameters($filterParameters,IndividualKYCMaster::RECORDS_PER_PAGE,$with);

            $response['html'] = view($this->module . $this->view . 'kyc-detail.individual-kyc',
                compact('individualsKyc','verificationStatuses','kycTypes','filterParameters',
                    'storeCode'))->render();
            return response()->json($response);

        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function showDetailKyc($kycCode)
    {
        try{
            $response =[];
            $individualKyc = $this->individualKycService->findOrFailIndividualKycEagerByCode($kycCode);
            $individualKyc = (new IndividualKycDetailTransformer($individualKyc))->transform();
            $response['html'] = view($this->module . $this->view . 'kyc-detail.show-detail-individual-kyc-detail',
                compact('individualKyc'
                    ))->render();
            return response()->json($response);

        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }

    }

    public function getFirmsKycForAdminSupport($storeCode,Request $request){

        try{
            $store = $this->storeService->findStoreByCode($storeCode);
            $filterParameters = [
                'business_registered_from' => $request->get('business_registered_from'),
                'store_name' => $store->store_name,
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

            $response['html'] = view($this->module . $this->view . 'kyc-detail.firm-kyc',
                compact('firmsKyc','filterParameters','verificationStatuses','businessRegistrationTypes'
                ))->render();

            return response()->json($response);

        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function showDetailFirmKyc($kycCode)
    {
        try{
            $firmKyc = $this->firmKycService->findOrFailFirmKycEagerByCode($kycCode);
            $firmKyc = (new FirmKycDetailTransformer($firmKyc))->transform();
            $response['html'] = view($this->module . $this->view . 'kyc-detail.show-detail-firm-kyc-detail',
                compact('firmKyc'
                ))->render();
            return response()->json($response);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }








//    public function newIndex($storeName,Request $request)
//    {
//        $filterParameters = [
//            'kyc_for' => $request->get('kyc_for'),
//            'store_name' => $storeName,
//            'verification_status' => $request->get('verification_status'),
//        ];
//
//        $kycTypes=IndividualKYCMaster::KYC_FOR_TYPES;
//        $verification_status = IndividualKYCMaster::VERIFICATION_STATUSES;
//
//        $kyclistings = StoreKycListingHelper::filterKycListing($filterParameters,25);
//
//
//        return view(Parent::loadViewData($this->module.$this->view.'listing.index'),
//            compact('kyclistings','filterParameters','kycTypes','verification_status'));
//    }



}



