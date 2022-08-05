<?php

namespace App\Modules\Vendor\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Modules\Vendor\Helpers\VendorActivityFilterHelper;
use App\Modules\Vendor\Resources\VendorActivityCollection;
use App\Modules\Vendor\Services\VendorActivityService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;


class VendorActivityController extends Controller
{
    protected $vendorActivityService;
    public function __construct(VendorActivityService $vendorActivityService){
        $this->vendorActivityService=$vendorActivityService;
    }

    public function getVendorActivity(Request $request){
        $userCode=getAuthUserCode();

        $startDate=Carbon::now();
        $endDate=Carbon::now()->subDays(10);
        $limit=$request['limit']??3;
        $select=['subject','created_at'];
        $filterParameters=[
            'userCode'=>getAuthUserCode(),
            'startDate'=>$request->startDate??Carbon::now()->subDay(10),
            'endDate'=>$request->endDate??Carbon::now(),
            'paginateBy'=>$request->paginatedBy??5,
            'select'=>['subject','created_at']
        ];
        try{
            $vendorActivity=VendorActivityFilterHelper::getVendorActivity($filterParameters);
            return new VendorActivityCollection($vendorActivity);


        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

}
