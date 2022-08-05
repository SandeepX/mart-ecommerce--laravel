<?php

namespace App\Modules\Variants\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Variants\Requests\Variant\VariantCreateRequest;
use App\Modules\Variants\Requests\Variant\VariantUpdateRequest;
use App\Modules\Variants\Resources\MinimalVariantResource;
use App\Modules\Variants\Services\VariantService;

class VariantController extends BaseController
{
    public $title = 'Variant';
    public $base_route = 'admin.variants';
    public $sub_icon = 'file';
    public $module = 'Variants::';


    private $view;
    private $variantService;


    public function __construct(VariantService $variantService)
    {
        $this->middleware('permission:View Variant List', ['only' => ['index']]);
        $this->middleware('permission:Create Variant', ['only' => ['create','store']]);
        $this->middleware('permission:Show Variant', ['only' => ['show']]);
        $this->middleware('permission:Update Variant', ['only' => ['edit','update']]);
        $this->middleware('permission:Delete Variant', ['only' => ['destroy']]);

        $this->view = 'admin.';
        $this->variantService = $variantService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $variants = $this->variantService->getAllVariants();
        return view(Parent::loadViewData($this->module.$this->view.'index'),compact('variants'));
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VariantCreateRequest $request)
    {
        $validated = $request->validated();
        try{
            $variant =  $this->variantService->storeVariant($validated);

        }catch(\Exception $exception){

            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return redirect()->back()->with('success', $this->title . ': '. $variant->variant_name .' Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($variantCode)
    {
        try{
            $variant = $this->variantService->findOrFailVariantByCode(
                $variantCode,['variantValues']
            );
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', 'No Such Resource Found');
        }
        return view(Parent::loadViewData($this->module.$this->view.'show'),compact('variant'));

    }


    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($variantCode)
    {
        try{
            $variant = $this->variantService->findOrFailVariantByCode($variantCode);
        }catch(\Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
        return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('variant'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VariantUpdateRequest $request,$variantCode)
    {
        $validated = $request->validated();
        try{
            $variant = $this->variantService->updateVariant($validated, $variantCode);
            return redirect()->back()->with('success', $this->title . ': '. $variant->variant_name .' Updated Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($variantCode)
    {
        try{
            $variant = $this->variantService->deleteVariant($variantCode);
            return redirect()->back()->with('success', $this->title . ': '. $variant->variant_name .' Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
