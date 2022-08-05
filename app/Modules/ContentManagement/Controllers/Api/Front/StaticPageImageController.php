<?php


namespace App\Modules\ContentManagement\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Modules\ContentManagement\Services\StaticPageImageService;
use App\Modules\ContentManagement\Resources\StaticPageImageCollection;

class StaticPageImageController extends Controller
{
    private $staticPageImageService;

    public function __construct(StaticPageImageService $staticPageImageService)
    {
        $this->staticPageImageService = $staticPageImageService;
    }

    public function getPageImages($page_name)
    {
        try{
            $allPageImage = $this->staticPageImageService->getAllImagesOfStaticPageByPageName($page_name);
            if($allPageImage){
                $allPageImage = new StaticPageImageCollection($allPageImage);
            }else{
                $allPageImage = '';
            }

            return sendSuccessResponse('Data Found',  $allPageImage);
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }


}
