<?php

namespace App\Modules\ContentManagement\Controllers\Web\Admin;

use App\Modules\ContentManagement\Requests\OurTeamCreateRequest;
use App\Modules\ContentManagement\Requests\OurTeamUpdateRequest;
use App\Modules\ContentManagement\Services\OurTeamService;
use App\Modules\Application\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

class OurTeamController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public $title = 'Our Teams';
    public $base_route = 'admin.our-teams';
    public $sub_icon = 'file';
    public $module = 'ContentManagement::';
    public $view = 'admin.our-team.';
    private $ourTeamService;
    public function __construct(OurTeamService $ourTeamService){
        $this->ourTeamService =$ourTeamService;
    }
    public function index()
    {
        try{
            $ourTeams = $this->ourTeamService->getAllOurTeam();
            return view(Parent::loadViewData($this->module.$this->view.'index'),compact('ourTeams'));
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function create()
    {
        return view(Parent::loadViewData($this->module.$this->view.'create'));
    }

    public function store(OurTeamCreateRequest $request)
    {
        $validatedData = $request->validated();
        try{
            $this->ourTeamService->storeOurTeam($validatedData);
            return redirect()->back()->with('success', 'Team Added Successfully');
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    public function show($ourTeamCode)
    {
        $ourTeam = $this->ourTeamService->findOrFailOurTeamByCode($ourTeamCode);
        return view(Parent::loadViewData($this->module.$this->view.'show'),compact('ourTeam'));
    }

    public function edit($ourTeam_Code)
    {

        try{
            $ourTeam = $this->ourTeamService->findOrFailOurTeamByCode($ourTeam_Code);
            return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('ourTeam'));
        }catch (Exception $ex){
            return redirect()->back()->with('danger',$ex->getMessage());
        }
    }

    public function update(OurTeamUpdateRequest $request, $ourTeam_Code)
    {
//        dd($request);
        $validatedData = $request->validated();
        try{
            $this->ourTeamService->updateOurTeam($validatedData, $ourTeam_Code);
            return redirect()->back()->with('success', 'Our Team Updated Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }


    public function destroy($ourTeam_Code)
    {
        try{
            $this->ourTeamService->deleteOurTeam($ourTeam_Code);
            return redirect()->back()->with('success', $this->title .'Our Team Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
