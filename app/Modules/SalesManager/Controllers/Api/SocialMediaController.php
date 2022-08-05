<?php


namespace App\Modules\SalesManager\Controllers\Api;


use App\Modules\SalesManager\Resources\SocialMediaCollection;
use App\Modules\SalesManager\Services\SocialMedia\SocialMediaService;
use Exception;

class SocialMediaController
{
    private $socialMediaService;

    public function __construct(SocialMediaService $socialMediaService){
        $this->socialMediaService = $socialMediaService;
    }

    public function getAllEnabledSocialMedia()
    {
        try {
            $socialMedia = $this->socialMediaService->getAllEnabledSocialMedia();
            $data = isset($socialMedia) ? new SocialMediaCollection($socialMedia):[];
            return sendSuccessResponse('Data Found',$data);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }





}
