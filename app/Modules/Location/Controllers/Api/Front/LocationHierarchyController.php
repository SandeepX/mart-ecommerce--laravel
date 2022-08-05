<?php

namespace App\Modules\Location\Controllers\Api\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Location\Resources\LocationHierarchy\LocationHierarchyResource;
use App\Modules\Location\Services\LocationHierarchyService;
use Exception;

class LocationHierarchyController extends Controller
{
    protected $locationHierarchyService;
    public function __construct(LocationHierarchyService $locationHierarchyService)
    {
        $this->locationHierarchyService = $locationHierarchyService;
    }

    public function getAllLocationsByType(Request $request){
        $this->validate($request,[
            'location_type' => 'required|string|max:255|in:country,province,district,municipality,ward,tole/street'
        ]);

        try{
            $locations = $this->locationHierarchyService->getAllLocationsByType($request->location_type);
            $locations = LocationHierarchyResource::collection($locations);
            return sendSuccessResponse('Data Found', $locations);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }


    }

    public function getLowerLocations($locationHierarchyCode){
        try{
            $lowerLocations = $this->locationHierarchyService->getLowerLocations($locationHierarchyCode);
            $lowerLocations = LocationHierarchyResource::collection($lowerLocations);
            return sendSuccessResponse('Data Found', $lowerLocations);
        }catch(Exception $exception){
           return sendErrorResponse($exception->getMessage(),$exception->getCode());

        }

    }

    public function getUpperLocation($locationHierarchyCode){
        try{
            $upperLocation = $this->locationHierarchyService->getUpperLocation($locationHierarchyCode);
            $upperLocation = new LocationHierarchyResource($upperLocation);
            return sendSuccessResponse('Data Found', $upperLocation);

        }catch(Exception $exception){
           return sendErrorResponse($exception->getMessage(),$exception->getCode());

        }

    }

    public function getLocationById($locationHierarchyCode){
        try{
            $location = $this->locationHierarchyService->getLocationById($locationHierarchyCode);
            $location = new LocationHierarchyResource($location);
            return sendSuccessResponse('Data Found', $location);

        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());

        }

    }

    public function getLocationByCode(Request $request){
        $this->validate($request,[
            'location_code' => 'required|string|max:255|exists:location_hierarchy,location_code'
        ]);

        try{
            $location = $this->locationHierarchyService->getLocationByCode($request->location_code);
            $location = new LocationHierarchyResource($location);
            return sendSuccessResponse('Data Found', $location);

        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());

        }

    }

    public function getLocationPath($locationHierarchyCode){
        try{
            return $this->locationHierarchyService->getLocationPath($locationHierarchyCode);

        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());

        }
    }

}
