<?php

namespace App\Modules\ContentManagement\Controllers\Web\Admin;

use App\Modules\ContentManagement\Requests\VisionCreateRequest;
use App\Modules\ContentManagement\Requests\VisionUpdateRequest;
use App\Modules\ContentManagement\Services\VisionService;
use App\Modules\Application\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class VisionMissionController extends BaseController
{
    public $title = 'Vision Mission';
    public $base_route = 'admin.vision-mission';
    public $sub_icon = 'file';
    public $module = 'ContentManagement::';
    public $view = 'admin.vision.';

    private $visionService;
    public function __construct(VisionService  $visionService){
        $this->visionService =$visionService;
    }
    public function index()
    {
        try{
            $visions = $this->visionService->getAllVisionMission();
            return view(Parent::loadViewData($this->module.$this->view.'index'),compact('visions'));
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function create()
    {
        return view(Parent::loadViewData($this->module.$this->view.'create'));
    }

    public function store(VisionCreateRequest $request)
    {
        $validatedData = $request->validated();
        try{
            $this->visionService->storeVisionMission($validatedData);
            return redirect()->back()->with('success', 'Vision Mission Created Successfully');
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    public function show($visionCode)
    {
        $vision = $this->visionService->findOrFailVisionMissionByCode($visionCode);
        return view(Parent::loadViewData($this->module.$this->view.'show'),compact('vision'));
    }

    public function edit($vision_Code)
    {
        try{
            $vision = $this->visionService->findOrFailVisionMissionByCode($vision_Code);
            return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('vision'));
        }catch (\Exception $ex){
            return redirect()->back()->with('danger',$ex->getMessage());
        }
    }

    public function update(VisionUpdateRequest $request, $visionCode)
    {
        $validatedData = $request->validated();
        try{
            $this->visionService->updateVisionMission($validatedData,$visionCode);
            return redirect()->back()->with('success', 'Vision Mission Updated Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    public function destroy($visionMissionCode)
    {
        try{
            $this->visionService->deleteVisionMission($visionMissionCode);
            return redirect()->back()->with('success', $this->title .' Vision Mission Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
