<?php

namespace App\Modules\Store\Controllers\Web\Admin;

use App\Modules\Store\Helpers\StoreKycListingHelper;
use App\Modules\Store\Models\Kyc\FirmKycMaster;
use App\Modules\Store\Models\Kyc\IndividualKYCMaster;
use App\Modules\Store\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Modules\Application\Controllers\BaseController;
use Illuminate\Support\Facades\DB;

class StoreKycListingController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    public $title = 'Stores Kyc Listings';
    public $base_route = 'admin.stores-kyc.';
    public $sub_icon = 'file';
    public $module = 'Store::';

    private $view='admin.kyc.';

    public function index(Request $request)
    {

        $filterParameters = [
            'kyc_for' => $request->get('kyc_for'),
            'store_name' => $request->get('store_name'),
            'verification_status' => $request->get('verification_status'),
        ];

        //dd($filterParameters);

        $kycTypes=IndividualKYCMaster::KYC_FOR_TYPES;

        $verification_status = IndividualKYCMaster::VERIFICATION_STATUSES;
        //dd($verification_status);

         $kyclistings = Store::when(isset($filterParameters['store_name']),function ($query) use($filterParameters) {
                 $query->where('store_name', 'like', '%' . $filterParameters['store_name'] . '%');
             })
             ->where(function($query) use ($filterParameters,$kycTypes){
                 if(isset($filterParameters['kyc_for']) && isset($filterParameters['verification_status'])) {
                     if (!in_array('firm', $filterParameters['kyc_for'])) {
                         $query->whereHas('individualKyc', function ($query) use ($filterParameters) {
                                 $query->whereIn('kyc_for', $filterParameters['kyc_for'])->where('verification_status',$filterParameters['verification_status']);
                         });
                     }
                     if (in_array('firm', $filterParameters['kyc_for'])) {
                         $query->whereHas('firmKyc', function ($query) use ($filterParameters) {
                             $query->where('verification_status',$filterParameters['verification_status']);
                         });
                     }
                 }elseif(isset($filterParameters['kyc_for'])){

                     if (!in_array('firm', $filterParameters['kyc_for'])) {
                         $query->whereHas('individualKyc', function ($query) use ($filterParameters) {
                             $query->whereIn('kyc_for', $filterParameters['kyc_for']);
                         });
                     }
                     if (in_array('firm', $filterParameters['kyc_for'])) {
                         $query->whereHas('firmKyc', function ($query) use ($filterParameters) {
                             $query;
                         });
                     }

                 }elseif(isset($filterParameters['verification_status'])){
                     $query->whereHas('individualKyc',function ($query) use ($filterParameters){
                         $query->where('verification_status', $filterParameters['verification_status']);
                     })->orWhereHas('firmKyc',function ($query) use ($filterParameters){
                         $query->where('verification_status',$filterParameters['verification_status']);
                     });
                 }else{
                     $query->has('individualKyc')->orHas('firmKyc');
                 }

             })

            ->paginate(25);

       //
        //dd($kyclistings);

        return view(Parent::loadViewData($this->module.$this->view.'listing.index'),compact('kyclistings','filterParameters','kycTypes','verification_status'));
    }

    public function newIndex(Request $request){

        $filterParameters = [
            'kyc_for' => $request->get('kyc_for'),
            'store_name' => $request->get('store_name'),
            'verification_status' => $request->get('verification_status'),
        ];

        $kycTypes=IndividualKYCMaster::KYC_FOR_TYPES;
        $verification_status = IndividualKYCMaster::VERIFICATION_STATUSES;

        $kyclistings = StoreKycListingHelper::filterKycListing($filterParameters,25);


        return view(Parent::loadViewData($this->module.$this->view.'listing.index'),compact('kyclistings','filterParameters','kycTypes','verification_status'));
    }


}
