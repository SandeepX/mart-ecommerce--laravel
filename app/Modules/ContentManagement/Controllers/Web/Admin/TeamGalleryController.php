<?php

namespace App\Modules\ContentManagement\Controllers\Web\Admin;

use App\Modules\ContentManagement\Requests\TeamGalleryCreateRequest;
use App\Modules\ContentManagement\Requests\TeamGalleryUpdateRequest;
use App\Modules\ContentManagement\Services\TeamGalleryService;
use App\Modules\Application\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

class TeamGalleryController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public $title = 'Team Gallery';
    public $base_route = 'admin.team-gallery';
    public $sub_icon = 'file';
    public $module = 'ContentManagement::';
    public $view = 'admin.team-gallery.';
    private $teamGalleryService;
    public function __construct(TeamGalleryService $teamGalleryService){
        $this->teamGalleryService =$teamGalleryService;
    }
    public function index()
    {
        try{
            $teamGalleries = $this->teamGalleryService->getAllTeamGallery();
            return view(Parent::loadViewData($this->module.$this->view.'index'),compact('teamGalleries'));
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function create()
    {
        return view(Parent::loadViewData($this->module.$this->view.'create'));
    }

    public function store(TeamGalleryCreateRequest $request)
    {
        $validatedData = $request->validated();
        try{
            $this->teamGalleryService->storeTeamGallery($validatedData);
            return redirect()->back()->with('success', 'Team Gallery Added Successfully');
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    public function show($teamGalleryCode)
    {
        $teamGallery = $this->teamGalleryService->findOrFailTeamGalleryByCode($teamGalleryCode);
        return view(Parent::loadViewData($this->module.$this->view.'show'),compact('teamGallery'));
    }

    public function edit($teamGallery_Code)
    {

        try{
            $teamGallery = $this->teamGalleryService->findOrFailTeamGalleryByCode($teamGallery_Code);
            return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('teamGallery'));
        }catch (Exception $ex){
            return redirect()->back()->with('danger',$ex->getMessage());
        }
    }

    public function update(TeamGalleryUpdateRequest $request, $teamGallery_Code)
    {
        $validatedData = $request->validated();
        try{
            $this->teamGalleryService->updateTeamGallery($validatedData, $teamGallery_Code);
            return redirect()->back()->with('success', 'Our Team Updated Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }


    public function destroy($teamGallery_Code)
    {
        try{
            $this->teamGalleryService->deleteTeamGallery($teamGallery_Code);
            return redirect()->back()->with('success', $this->title .'Team Gallery Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
