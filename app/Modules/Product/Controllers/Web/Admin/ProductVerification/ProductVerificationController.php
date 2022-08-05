<?php

namespace App\Modules\Product\Controllers\Web\Admin\ProductVerification;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Product\Requests\ProductVerification\ProductVerificationRequest;
use App\Modules\Product\Services\ProductService;
use App\Modules\Product\Services\ProductVerification\ProductVerificationService;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductVerificationController extends BaseController 
{
    private $productVerificationService;
    private $productService;
    public function __construct(ProductVerificationService $productVerificationService, ProductService $productService)
    {
        $this->middleware('permission:Verify Product', ['only' => ['storeProductVerification']]);

        $this->productVerificationService = $productVerificationService;
        $this->productService = $productService;
    }

    public function storeProductVerification(ProductVerificationRequest $productVerificationRequest, $productCode)
    {
        $validatedProductVerification = $productVerificationRequest->validated();
        // DB::beginTransaction();
        try{
            $product = $this->productService->findOrFailProductByCode($productCode);
            $this->productVerificationService->storeProductVerification($validatedProductVerification, $product);
            // DB::commit();
            return redirect()->back()->with('success', 'Verification for'.$product->product_name.'Done Successfully');
            
        }catch(Exception $exception){
            // DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
        
    }
}