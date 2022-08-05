<?php

namespace App\Modules\Location\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Location\Requests\LocationHierarchy\LocationHierarchyCreateRequest;
use App\Modules\Location\Requests\LocationHierarchy\LocationHierarchyUpdateRequest;
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
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $locations = $this->locationHierarchyService->getAllLocations();
            $locations = LocationHierarchyResource::collection($locations);
            return sendSuccessResponse('Data Found',  $locations);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 402);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LocationHierarchyCreateRequest $request)
    {
        try{
            $validated = $request->validated();
            $locationHierarchy = $this->locationHierarchyService->create($validated);
            $locationHierarchy = new LocationHierarchyResource($locationHierarchy);

        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
        return sendSuccessResponse('Location Hierarchy Created Successfully',  $locationHierarchy);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($locationHierarchyCode)
    {
        try{
            $location = $this->locationHierarchyService->getLocationByCode($locationHierarchyCode);
            $locations = new LocationHierarchyResource($location);
            return sendSuccessResponse('Data Found',  $location);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 402);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LocationHierarchyUpdateRequest $request, $locationHierarchyCode)
    {
        $validated = $request->validated();
        $locationHierarchy = $this->locationHierarchyService->update($validated, $locationHierarchyCode);
        $locationHierarchy = new LocationHierarchyResource($locationHierarchy);
        return sendSuccessResponse('Location Hierarchy Updated Successfully',  $locationHierarchy);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($locationHierarchyCode)
    {
        $locationHierarchy = $this->locationHierarchyService->delete($locationHierarchyCode);
        $locationHierarchy = new LocationHierarchyResource($locationHierarchy);
        return sendSuccessResponse('Location Hierarchy Deleted Successfully',  $locationHierarchy);
    }
}
