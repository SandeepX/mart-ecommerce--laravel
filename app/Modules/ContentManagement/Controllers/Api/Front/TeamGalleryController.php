<?php

namespace App\Modules\ContentManagement\Controllers\Api\Front;

use App\Modules\ContentManagement\Resources\TeamGalleryCollection;
use App\Modules\ContentManagement\Resources\TeamGalleryResource;
use App\Modules\ContentManagement\Resources\VisionMissionResource;
use App\Modules\ContentManagement\Services\TeamGalleryService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class TeamGalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
//'image' => photoToUrl($this->image,url(PVGroupBulkImage::IMAGE_PATH))
    private $teamGalleryService;
    public function __construct(TeamGalleryService  $teamGalleryService){
        $this->teamGalleryService =$teamGalleryService;
    }
    public function index(Request $request)
    {
        try{
            $select=['image','description'];
            $paginate = isset($request->paginate) ? $request->paginate : 5;
            $teamGallery= $this->teamGalleryService->getLatestActiveTeamGallery($select,$paginate);
            return new TeamGalleryCollection($teamGallery);
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }



}
