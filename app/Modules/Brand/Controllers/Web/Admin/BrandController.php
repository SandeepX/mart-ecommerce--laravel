<?php

namespace App\Modules\Brand\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Brand\Requests\BrandCreateRequest;
use App\Modules\Brand\Requests\BrandUpdateRequest;
use App\Modules\Brand\Services\BrandService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BrandController extends BaseController
{

    public $title = 'Brand';
    public $base_route = 'admin.brands';
    public $sub_icon = 'file';
    public $module = 'Brand::';
    public $view = 'admin.';

    private $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->middleware('permission:View Brand List', ['only' => ['index']]);
        $this->middleware('permission:Create Brand', ['only' => ['create','store']]);
        $this->middleware('permission:Show Brand', ['only' => ['show']]);
        $this->middleware('permission:Update Brand', ['only' => ['edit','update']]);
        $this->middleware('permission:Delete Brand', ['only' => ['destroy']]);

        $this->brandService = $brandService;

    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $brands = $this->brandService->getAllBrands();
        return view(Parent::loadViewData($this->module.$this->view.'index'),compact('brands'));
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
    public function store(BrandCreateRequest $request)
    {
        $validated = $request->validated();
        try{
            $brand =  $this->brandService->storeBrand($validated);
            return redirect()->back()->with('success', $this->title . ': '. $brand->brand_name .' Created Successfully');
        }catch(\Exception $exception){
             return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    /**
     * Show the specified resource.
     * @return Response
     */
//    public function show($brandSlug)
//    {
//        return view('Brand::show');
//    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($brandCode)
    {
        try{
            $brand = $this->brandService->findOrFailBrandByCode($brandCode);
            return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('brand'));
        }catch (\Exception $ex){
            return redirect()->back()->with('danger',$ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(BrandUpdateRequest $request, $brand)
    {
        $validated = $request->validated();
        try{
           $brand = $this->brandService->updateBrand($validated, $brand);
           return redirect()->back()->with('success', $this->title . ': '. $brand->brand_name .' Updated Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }

    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($brandSlug)
    {
        try{
            $brand = $this->brandService->deleteBrand($brandSlug);
            return redirect()->back()->with('success', $this->title . ': '. $brand->brand_name .' Brand Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
