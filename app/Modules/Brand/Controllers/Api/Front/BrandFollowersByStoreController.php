<?php

namespace App\Modules\Brand\Controllers\Api\Front;

use App\Modules\Brand\Requests\BrandFollowersByStoreRequest;
use App\Modules\Brand\Resources\BrandFollowerNumberResource;
use App\Modules\Brand\Services\BrandFollowersByStoreService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class BrandFollowersByStoreController extends Controller
{

    protected $brandFollowersByStoreService;

    public function __construct(BrandFollowersByStoreService $brandFollowersByStoreService){
        $this->brandFollowersByStoreService =$brandFollowersByStoreService;
    }

    public function createOrUpdateBrandFollow($brandCode)
    {

        try{
            $brandFollow=$this->brandFollowersByStoreService->createOrUpdateBrandFollowByStore($brandCode);
            $data=$this->brandFollowersByStoreService->countBrandFollowerByStoreByBrandCode($brandCode);
            $message =  NULL;
            $follow=false;
            if($brandFollow['flag'] == 1)
            {
               $message ='Brand Unfollowing';
               $follow=false;
            }
            elseif ($brandFollow['flag'] == 2)
            {
                $message ='Brand Following';
                $follow=True;
            }
            else{
               $message="Brand Following";
               $follow=True;
            }
            $followerData=['message'=>$message,'follow'=>$follow, 'data'=>$data];
            return new BrandFollowerNumberResource($followerData);

        }
        catch (\Exception $exception){
            return sendErrorResponse($exception->getMessage(),400);
        }
    }
    public function isBrandFollowed($brandCode){
        try{
            $brandFollowed=$this->brandFollowersByStoreService->findOrFailBrandFollowByStore($brandCode);
            $data['is_followed'] = isset($brandFollowed) ? true : false;
            return sendSuccessResponse('Brand Status',$data);
        }catch (\Exception $exception){
            return sendErrorResponse($exception->getMessage(),400);
        }



    }
//    public function countBrandFollowerByStoreCode($brandCode){
//        $data=$this->brandFollowersByStoreService->countBrandFollowerByStoreByBrandCode($brandCode);
//        $message="Number of Follower";
//        return sendSuccessResponse($message,$data);
////        return new BrandFollowerNumberResource($data);
//    }


}
