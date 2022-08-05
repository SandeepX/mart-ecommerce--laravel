<?php

namespace App\Modules\Brand\Controllers\Web\Admin;

use App\Modules\Brand\Requests\BrandSliderCreateRequest;
use App\Modules\Brand\Requests\BrandSliderUpdateRequest;
use App\Modules\Brand\Services\BrandService;
use App\Modules\Brand\Services\BrandSliderService;
use App\Modules\Application\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

class BrandSliderController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public $title = 'Brand Slider';
    public $base_route = 'admin.brand-sliders';
    public $sub_icon = 'file';
    public $module = 'Brand::';
    public $view = 'admin.brand-slider.';
    private $brandSliderService;
    private $brandService;
    public function __construct(BrandSliderService $brandSliderService,BrandService $brandService){
        $this->brandSliderService =$brandSliderService;
        $this->brandService=$brandService;
    }
    public function index($brandCode)
    {
        $brand= $this->brandService->findOrFailBrandByCode($brandCode);
        try{
            $brandSliders = $this->brandSliderService->getAllBrandSlider($brand);
            return view(Parent::loadViewData($this->module.$this->view.'index'),compact('brandSliders','brand'));
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function create($brandCode)
    {

        $brand= $this->brandService->findOrFailBrandByCode($brandCode);
//        dd($brand);
        return view(Parent::loadViewData($this->module.$this->view.'create'),compact('brand'));
    }

    public function store(BrandSliderCreateRequest $request)
    {
        $validatedData = $request->validated();
        try{
            $this->brandSliderService->createBrandSlider($validatedData);
            return redirect()->back()->with('success', 'Brand Slider Added Successfully');
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    public function show($brandCode,$brandSliderCode)
    {
        $brand= $this->brandService->findOrFailBrandByCode($brandCode);
        $brandSlider = $this->brandSliderService->findOrFailBrandSliderByCode($brandSliderCode);
        return view(Parent::loadViewData($this->module.$this->view.'show'),compact('brand','brandSlider'));
    }

    public function edit($brandCode,$brandSlider_Code)
    {
        $brand= $this->brandService->findOrFailBrandByCode($brandCode);
        try{
            $brandSlider = $this->brandSliderService->findOrFailBrandSliderByCode($brandSlider_Code);
            return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('brandSlider','brand'));
        }catch (Exception $ex){
            return redirect()->back()->with('danger',$ex->getMessage());
        }
    }

    public function update(BrandSliderUpdateRequest $request, $brandSlider_Code)
    {

        $validatedData = $request->validated();

        try{
            $this->brandSliderService->updateBrandSlider($validatedData, $brandSlider_Code);
            return redirect()->back()->with('success', 'Our Team Updated Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }


    public function destroy($brandSlider_Code)
    {
        try{
            $this->brandSliderService->deleteBrandSlider($brandSlider_Code);
            return redirect()->back()->with('success', $this->title .'Our Team Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
