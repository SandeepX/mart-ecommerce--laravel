<?php

namespace App\Modules\Vendor\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Modules\Vendor\Resources\VendorResource;
use Exception;

class VendorDetailController extends Controller
{
   
    public function getVendorDetail(){
        try{
            $vendorDetail = new VendorResource(auth()->user()->vendor);
            return sendSuccessResponse('Data Found', $vendorDetail);

        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

}