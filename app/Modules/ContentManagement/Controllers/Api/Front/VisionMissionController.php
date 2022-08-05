<?php

namespace App\Modules\ContentManagement\Controllers\Api\Front;

use App\Modules\ContentManagement\Resources\VisionMissionResource;
use App\Modules\ContentManagement\Services\VisionService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class VisionMissionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
//'image' => photoToUrl($this->image,url(PVGroupBulkImage::IMAGE_PATH))
    private $visionService;
    public function __construct(VisionService  $visionService){
        $this->visionService =$visionService;
    }
    public function index()
    {
        try{
            $select=['page_image','vision_description','mission_description'];
            $visionMission= $this->visionService->getLatestActiveVisionMission($select);
            return new VisionMissionResource($visionMission);
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }


}
