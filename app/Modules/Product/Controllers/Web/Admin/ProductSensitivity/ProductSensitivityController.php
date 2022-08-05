<?php

namespace App\Modules\Product\Controllers\Web\Admin\ProductSensitivity;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Product\Requests\ProductSensitivity\ProductSensitivityCreateRequest;
use App\Modules\Product\Requests\ProductSensitivity\ProductSensitivityUpdateRequest;
use App\Modules\Product\Services\ProductSensitivity\ProductSensitivityService;

class ProductSensitivityController extends BaseController
{

    public $title = 'Product Sensitivity';
    public $base_route = 'admin.product-sensitivities';
    public $sub_icon = 'file';
    public $module = 'Product::';


    private $view;
    private $productSensitivityService;

    public function __construct(ProductSensitivityService $productSensitivityService)
    {
        $this->middleware('permission:View Product Sensitivity List', ['only' => ['index']]);
        $this->middleware('permission:Create Product Sensitivity', ['only' => ['create','store']]);
        $this->middleware('permission:Show Product Sensitivity', ['only' => ['show']]);
        $this->middleware('permission:Update Product Sensitivity', ['only' => ['edit','update']]);
        $this->middleware('permission:Delete Product Sensitivity', ['only' => ['destroy']]);

        $this->view = 'admin.product-sensitivity.';
        $this->productSensitivityService = $productSensitivityService;

    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $productSensitivities = $this->productSensitivityService->getAllProductSensitivities();
        return view(Parent::loadViewData($this->module.$this->view.'index'),compact('productSensitivities'));
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
    public function store(ProductSensitivityCreateRequest $request)
    {
        $validated = $request->validated();
        try{
            $productSensitivity =  $this->productSensitivityService->storeProductSensitivity($validated);
            return redirect()->back()->with('success', $this->title . ': '. $productSensitivity->sensitivity_name .' Product Sensitivity Created Successfully');
        }catch(\Exception $exception){
             return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($ProductSensitivityCode)
    {
        return view('Product::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($productSensitivityCode)
    {
        try{
            $productSensitivity = $this->productSensitivityService->findOrFailProductSensitivityByCode($productSensitivityCode);
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }

        return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('productSensitivity'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(ProductSensitivityUpdateRequest $request, $productSensitivityCode)
    {
        try{
            $validated = $request->validated();
            $productSensitivity = $this->productSensitivityService->updateProductSensitivity($validated, $productSensitivityCode);

        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return redirect()->back()->with('success', $this->title . ': '. $productSensitivity->sensitivity_name .'  Updated Successfully');

    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($productSensitivityCode)
    {
        try{
            $productSensitivity = $this->productSensitivityService->deleteProductSensitivity($productSensitivityCode);
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return redirect()->back()->with('success', $this->title . ': '. $productSensitivity->sensitivity_name .'  Trashed Successfully');
    }
}
