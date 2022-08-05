<?php

namespace App\Modules\Variants\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Variants\Requests\VariantValue\VariantValueCreateRequest;
use App\Modules\Variants\Requests\VariantValue\VariantValueUpdateRequest;
use App\Modules\Variants\Services\VariantValueService;


class VariantValueController extends Controller
{
    private $variantValueService;

    public $title = 'Variant Value';


    public function __construct(VariantValueService $variantValueService)
    {
        //$this->middleware('permission:View Variant Value List', ['only' => ['index']]);
        $this->middleware('permission:Create Variant Value', ['only' => ['store']]);
       // $this->middleware('permission:Show Variant Value', ['only' => ['show']]);
        $this->middleware('permission:Update Variant Value', ['only' => ['update']]);
        $this->middleware('permission:Delete Variant Value', ['only' => ['destroy']]);

        $this->view = 'admin.';
        $this->variantValueService = $variantValueService;
    }


    public function store(VariantValueCreateRequest $request,$variantID)
    {
        $validated = $request->validated();
        try{
            $variant =  $this->variantValueService->storeVariantValue($validated,$variantID);
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return redirect()->back()->with('success', $this->title . ': '. $variant->variant_value_name .' Created Successfully');
    }

    public function update(VariantValueUpdateRequest $request,$variantValueCode)
    {
        $validated = $request->validated();
        try{
            $variant = $this->variantValueService->updateVariantValue($validated, $variantValueCode);

            
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return redirect()->back()->with('success', $this->title . ': '. $variant->variant_value_name .' Updated Successfully');
    }

    public function destroy($variantValueCode)
    {
        try{
            $variant = $this->variantValueService->deleteVariantValue($variantValueCode);
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return redirect()->back()->with('success', $this->title . ': '. $variant->variant_value_name .' Trashed Successfully');
    }
}
