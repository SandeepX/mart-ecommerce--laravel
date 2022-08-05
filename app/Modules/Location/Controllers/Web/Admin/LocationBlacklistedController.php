<?php


namespace App\Modules\Location\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Location\Helpers\BlacklistedLocationFilter;
use App\Modules\Location\Helpers\LocationHierarchyFilter;
use App\Modules\Location\Repositories\LocationHierarchyRepository;
use App\Modules\Location\Requests\BlacklistedLocation\BlacklistedLocationRequest;
use App\Modules\Location\Services\LocationBlacklistedService;
use Illuminate\Http\Request;

class LocationBlacklistedController extends BaseController
{
    public $title = 'Location BlackList';
    public $base_route = 'admin.location-blacklisted';
    public $sub_icon = 'file';
    public $module = 'Location::';
    private $view = 'admin.location-blacklisted.';

    public $blacklistedLocation;

    public function __construct(LocationBlacklistedService $blacklistedLocation)
    {
        $this->blacklistedLocation = $blacklistedLocation;
    }

    public function index(Request $request)
    {
        try{
            $filterParameters  = [
                'location_name' => $request->get('location_name'),
                'status' => $request->get('status'),
                'from_date' => $request->get('from_date'),
                'to_date' => $request->get('to_date'),
            ];

            $getAllBlackListedLocation = BlacklistedLocationFilter::filter($filterParameters,20);

            //$getAllBlackListedLocation = $this->blacklistedLocation->getALlBlacklistedLocation();

            return view(Parent::loadViewData($this->module . $this->view . 'index'),
                compact('getAllBlackListedLocation','filterParameters')
            );
        }catch (\Exception $exception){
            return redirect()->route('admin.dashboard')->with('danger',$exception->getMessage());
        }
    }

    public function create()
    {
        try{
            return view(Parent::loadViewData($this->module . $this->view . 'create'));
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }

    }

    public function store(BlacklistedLocationRequest $request)
    {
        try{
            $validatedData = $request->validated();
            $locationBlacklist = $this->blacklistedLocation->getBlacklistedLocationByLocationCode($validatedData['location_code']);
            if($locationBlacklist){
                throw new \Exception('Location is already in BlackList');
            }
            $blackListLocation = $this->blacklistedLocation->store($validatedData);
            return redirect()->back()->with('success','Location With Code ' .$validatedData['location_code'] . ': Blacklisted Successfully');

        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }

    }

    public function edit($BLHCode)
    {
        try{
            $blackListedLocationDetail = $this->blacklistedLocation->getBlacklistedLocationByBLHCode($BLHCode);
            $location = (new LocationHierarchyRepository)->getLocationByCode($blackListedLocationDetail['location_code']);
            $locationTree = (new LocationHierarchyRepository)->getLocationPath($location);
            return view(Parent::loadViewData($this->module . $this->view . 'edit'),
                compact('blackListedLocationDetail',
                'locationTree')
            );
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    public function update(BlacklistedLocationRequest $request,$BLHCode)
    {
        try{
            $validatedData = $request->validated();
            $blackListLocation = $this->blacklistedLocation->update($validatedData,$BLHCode);
            return redirect()->back()->with('success','BlackListed Location Detail Updated Successfully');
        }catch(\Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function destroy($BLHCode)
    {
        try{
            $blackListedLocation =  $this->blacklistedLocation->delete($BLHCode);
            return redirect()->back()->with('success', ' BlackListed Location Detail Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }

    }

    public function toggleStatus($BLHCode)
    {
        try{
            $investmentPlan = $this->blacklistedLocation->changeBlacklistedLocationStatus($BLHCode);
            return redirect()->route('admin.location-blacklisted.index')->with('success','Status changed Successfully');
        }catch(\Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }
}




