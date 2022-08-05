<?php

namespace App\Modules\Location\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Location\Helpers\LocationHierarchyFilter;
use App\Modules\Location\Requests\LocationHierarchy\LocationHierarchyCreateRequest;
use App\Modules\Location\Requests\LocationHierarchy\LocationHierarchyUpdateRequest;
use App\Modules\Location\Services\LocationHierarchyService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LocationHierarchyController extends BaseController
{

    public $title = 'Location Hierarchy';
    public $base_route = 'admin.location-hierarchies';
    public $sub_icon = 'file';
    public $module = 'Location::';


    private $view;
    private $locationHierarchyService;

    public function __construct(LocationHierarchyService $locationHierarchyService)
    {
        $this->middleware('permission:Create Location', ['only' => ['create']]);
        $this->middleware('permission:View Location List', ['only' => ['index']]);

        $this->view = 'admin.';
        $this->locationHierarchyService = $locationHierarchyService;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {

        $filterParameters = [
            'location_name' => $request->get('location_name'),
            'location_type' => $request->get('location_type'),
            'province' => $request->get('province'),
            'district' => $request->get('district'),
            'municipality' => $request->get('municipality'),
            'ward' => $request->get('ward'),
        ];

        $with=['nestedLowerLocations'];
       // $locationHierarchies = $this->locationHierarchyService->getAllLocationsByType('province');
        $locationHierarchies = LocationHierarchyFilter::filterPaginatedLocations($filterParameters,10,$with);
      //dd($locationHierarchies);
        $provinces = $this->locationHierarchyService->getAllLocationsByType('province');
        $locationTypes = ['province','district','municipality','ward'];
        return view(Parent::loadViewData($this->module.$this->view.'index'),compact('locationHierarchies',
            'provinces','locationTypes','filterParameters'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {

        $provinces = $this->locationHierarchyService->getAllLocationsByType('province');
        $locationTypes = ['district','municipality','ward'];
        return view(Parent::loadViewData($this->module.$this->view.'create'),compact('provinces',
            'locationTypes'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(LocationHierarchyCreateRequest $request)
    {
        try{
            $validated = $request->validated();
            $locationHierarchy =  $this->locationHierarchyService->createLocation($validated);
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(),  400);
            //  return redirect()->back()->with('danger', $exception->getMessage());
        }
        return sendSuccessResponse($this->title . ': '. $locationHierarchy->location_name .' Created Successfully',  200);
        // return redirect()->back()->with('success', $this->title . ': '. $locationHierarchy->location_name .' Created Successfully');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($LocationHierarchyCode)
    {
        return view('Product::show');
    }

    // /**
    //  * Show the form for editing the specified resource.
    //  * @return Response
    //  */
    // public function edit($LocationHierarchySlug)
    // {
    //     try{
    //         $locationHierarchy = $this->locationHierarchyService->getLocationBySlug($LocationHierarchySlug);
    //     }catch(Exception $exception){
    //         return redirect()->back()->with('danger', $exception->getMessage());
    //     }
    //     return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('locationHierarchy'));
    // }

    // /**
    //  * Update the specified resource in storage.
    //  * @param  Request $request
    //  * @return Response
    //  */
    // public function update(LocationHierarchyUpdateRequest $request, $LocationHierarchyCode)
    // {
    //     try{
    //         $validated = $request->validated();
    //         $validated['slug'] = Str::slug($validated['location_name']);
    //         $locationHierarchy = $this->locationHierarchyService->update($validated, $LocationHierarchyCode);
    //         return redirect()->route($this->base_route.'.edit', $locationHierarchy->slug)->with('success', $this->title . ': '. $locationHierarchy->location_name .' Updated Successfully');
    //     }catch (\Exception $exception){
    //         return redirect()->back()->with('danger', $exception->getMessage());
    //     }

    // }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    // public function destroy($LocationHierarchyCode)
    // {
    //     try{
    //         $LocationHierarchy = $this->locationHierarchyService->delete($LocationHierarchyCode);
    //         return redirect()->back()->with('success', $this->title . ': '. $LocationHierarchy->sensitivity_name .' Trashed Successfully');
    //     }catch (\Exception $exception){
    //         return redirect()->back()->with('danger', $exception->getMessage());
    //     }
    // }
}
