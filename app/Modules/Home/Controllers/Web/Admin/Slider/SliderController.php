<?php

namespace App\Modules\Home\Controllers\Web\Admin\Slider;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Home\Services\Slider\SliderService;
use App\Modules\Home\Requests\Slider\SliderCreateRequest;
use App\Modules\Home\Requests\Slider\SliderUpdateRequest;
use Exception;
use Illuminate\Support\Facades\DB;

class SliderController extends BaseController
{

    public $title = 'Slider';
    public $base_route = 'admin.sliders';
    public $sub_icon = 'file';
    public $module = 'Home::';


    private $view;
    private $sliderService;

    public function __construct(
        SliderService $sliderService
        )
    {
        $this->middleware('permission:View Slider List', ['only' => ['index']]);
        $this->middleware('permission:Create Slider', ['only' => ['create','store']]);
        $this->middleware('permission:Show Slider', ['only' => ['show']]);
        $this->middleware('permission:Update Slider', ['only' => ['edit','update']]);
        $this->middleware('permission:Delete Slider', ['only' => ['destroy']]);

        $this->view = 'admin.sliders.';
        $this->sliderService = $sliderService;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $sliders= $this->sliderService->getAllSliders();
        return view(Parent::loadViewData($this->module.$this->view.'index'),compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view(Parent::loadViewData($this->module.$this->view.'create'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(SliderCreateRequest $request)
    {
        $validated = $request->validated();
        DB::beginTransaction();
        try{
            $slider =  $this->sliderService->storeSlider($validated);
            DB::commit();
            return redirect()->back()->with('success', 'Slider Created Successfully');
        }catch(\Exception $exception){
            DB::rollBack();
             return redirect()->back()->with('danger', $exception->getMessage());
        }
    }


    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($sliderCode)
    {
        try{
            $slider = $this->sliderService->findOrFailSliderByCode($sliderCode);
            return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('slider'));
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(SliderUpdateRequest $request, $sliderCode)
    {
        DB::beginTransaction();
        try{
            $validated = $request->validated();
            $this->sliderService->updateSlider($validated, $sliderCode);

            DB::commit();
            return redirect()->back()->with('success', 'Slider Updated Successfully');

        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($sliderCode)
    {
        DB::beginTransaction();
        try{
             $this->sliderService->deleteSlider($sliderCode);
            DB::commit();
            return redirect()->back()->with('success','Slider Trashed Successfully');
        }catch (\Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
        
    }




}
