<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/21/2020
 * Time: 6:02 PM
 */

namespace App\Modules\Store\Repositories\Kyc;


use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\Store\Models\Kyc\KycAgreementVideo;

use Exception;

class KycVideoAgreementRepository
{

    use ImageService;

    public function findByStoreCode($storeCode,$videoFor){

        return KycAgreementVideo::where('store_code',$storeCode)->where('agreement_video_for',$videoFor)->first();
    }

    public function getVideosOfStore($storeCode){

        return KycAgreementVideo::where('store_code',$storeCode)->whereIn('agreement_video_for',KycAgreementVideo::AGREEMENT_VIDEO_FOR_TYPES)->get();

    }

    public function save($validatedData){

        $fileNameToStore='';
        try{
            $validatedData['user_code'] = getAuthUserCode();

            $fileNameToStore = $this->storeImageInServer($validatedData['video_file'], KycAgreementVideo::VIDEO_UPLOAD_PATH);

            $validatedData['agreement_video_name']= $fileNameToStore;

            return KycAgreementVideo::create($validatedData)->fresh();
        }catch (Exception $exception){
            $this->deleteImageFromServer(KycAgreementVideo::VIDEO_UPLOAD_PATH,$fileNameToStore);
            throw $exception;
        }

    }
}