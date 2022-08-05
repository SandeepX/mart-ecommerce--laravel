<?php

namespace App\Modules\Product\Controllers\Web\Admin\ProductWarranty;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Product\Requests\ProductWarranty\ProductWarrantyCreateRequest;
use App\Modules\Product\Requests\ProductWarranty\ProductWarrantyUpdateRequest;
use App\Modules\Product\Services\ProductWarranty\ProductWarrantyService;

class ProductWarrantyController extends BaseController
{

    public $title = 'Product Warranty';
    public $base_route = 'admin.product-warranties';
    public $sub_icon = 'file';
    public $module = 'Product::';


    private $view;
    private $productWarrantyService;

    public function __construct(ProductWarrantyService $productWarrantyService)
    {
        $this->middleware('permission:View Product Warranty List', ['only' => ['index']]);
        $this->middleware('permission:Create Product Warranty', ['only' => ['create','store']]);
        $this->middleware('permission:Show Product Warranty', ['only' => ['show']]);
        $this->middleware('permission:Update Product Warranty', ['only' => ['edit','update']]);
        $this->middleware('permission:Delete Product Warranty', ['only' => ['destroy']]);

        $this->view = 'admin.product-warranty.';
        $this->productWarrantyService = $productWarrantyService;

    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $productWarranties = $this->productWarrantyService->getAllProductWarranties();
        return view(Parent::loadViewData($this->module.$this->view.'index'),compact('productWarranties'));
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
    public function store(ProductWarrantyCreateRequest $request)
    {
        $validated = $request->validated();
        try{
            $productWarranty =  $this->productWarrantyService->storeProductWarranty($validated);
            return redirect()->back()->with('success', $this->title . ': '. $productWarranty->warranty_name .' Product Warranty Created Successfully');
        }catch(\Exception $exception){
             return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($ProductWarrantyCode)
    {
        return view('Product::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($productWarrantyCode)
    {
        try{
            $productWarranty = $this->productWarrantyService->findOrFailProductWarrantyByCode($productWarrantyCode);
        }catch (\Exception $exception){
           return redirect()->back()->with('danger',$exception->getMessage());
        }

        return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('productWarranty'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(ProductWarrantyUpdateRequest $request, $productWarrantyCode)
    {

        $validated = $request->validated();
        try{
            $productWarranty = $this->productWarrantyService->updateProductWarranty($validated, $productWarrantyCode);
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return redirect()->back()->with('success', $this->title . ': '. $productWarranty->warranty_name .' Updated Successfully');

    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($productWarrantyCode)
    {
        try{
            $productWarranty = $this->productWarrantyService->deleteProductWarranty($productWarrantyCode);
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return redirect()->back()->with('success', $this->title . ': '. $productWarranty->warranty_name .' Trashed Successfully');
    }
}
