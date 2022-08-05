<?php

namespace App\Modules\Product\Controllers\Web\Admin\ProductCollection;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Brand\Services\BrandService;
use App\Modules\Product\Helpers\ProductCollectionHelper;
use App\Modules\Product\Models\ProductMaster;
use App\Modules\Product\Requests\ProductCollection\AddProductstoCollectionRequest;
use App\Modules\Product\Services\ProductCollection\ProductCollectionService;
use App\Modules\Product\Requests\ProductCollection\ProductCollectionCreateRequest;
use App\Modules\Product\Requests\ProductCollection\ProductCollectionUpdateRequest;
use App\Modules\Product\Services\ProductService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductCollectionController extends BaseController
{

    public $title = 'Product Collection';
    public $base_route = 'admin.product-collections';
    public $sub_icon = 'file';
    public $module = 'Product::';


    private $view;
    private $productCollectionService;
    private $brandService;
    private $productService;

    public function __construct(
        ProductCollectionService $productCollectionService,
        BrandService $brandService,
        ProductService $productService
        )
    {

        $this->middleware('permission:View Product Collection List', ['only' => ['index']]);
        $this->middleware('permission:Create Product Collection', ['only' => ['create','store']]);
        $this->middleware('permission:Show Product Collection', ['only' => ['show']]);
        $this->middleware('permission:Update Product Collection', ['only' => ['edit','update']]);
        $this->middleware('permission:Delete Product Collection', ['only' => ['destroy']]);
        $this->middleware('permission:Change Product Collection Status', ['only' => ['updateProductCollectionStatus']]);
        $this->middleware('permission:Add Products In Product Collection', ['only' => ['showProductAdditionInCollection','addProductsToCollection','removeProductFromCollection','toggleStatus']]);
        $this->view = 'admin.product-collection.';
        $this->productCollectionService = $productCollectionService;
        $this->brandService = $brandService;
        $this->productService = $productService;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        try{

            $filterParameters = [
                'collection_title' => $request->get('collection_title'),
            ];

            $with =[
                'products' =>function($query){
                   return $query->qualifiedToDisplay();
                }
            ];

           // $productCollections= $this->productCollectionService->getAllProductCollections();
            $productCollections=ProductCollectionHelper::filterPaginatedProductCollections($filterParameters,10,$with);
            return view(Parent::loadViewData($this->module.$this->view.'index'),compact('productCollections','filterParameters'));

        }catch (Exception $e){
            return redirect()->route('admin.dashboard')->with('danger',$e->getMessage());
        }



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
    public function store(ProductCollectionCreateRequest $request)
    {
        $validated = $request->validated();
        DB::beginTransaction();
        try{
            $productCollection =  $this->productCollectionService->storeProductCollection($validated);
            DB::commit();
            return redirect()->back()->with('success', $this->title . ': '. $productCollection->product_collection_name .' Product Collection Created Successfully');
        }catch(\Exception $exception){
            DB::rollBack();
             return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($productCollectionCode)
    {
        try{
            $productCollection = $this->productCollectionService->findOrFailProductCollectionByCode($productCollectionCode);
            $productCollection->load('products');
            return view(Parent::loadViewData($this->module.$this->view.'show'),compact('productCollection'));
        }catch(Exception $exception){

            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($productCollectionCode)
    {
        try{
            $productCollection = $this->productCollectionService->findOrFailProductCollectionByCode($productCollectionCode);
            return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('productCollection'));
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(ProductCollectionUpdateRequest $request, $productCollectionCode)
    {
        DB::beginTransaction();
        try{
            $validated = $request->validated();
            $productCollection = $this->productCollectionService->updateProductCollection($validated, $productCollectionCode);

            DB::commit();
            return redirect()->back()->with('success', $this->title . ': '. $productCollection->product_collection_name .'  Updated Successfully');

        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($productCollectionCode)
    {
        DB::beginTransaction();
        try{
            $productCollection = $this->productCollectionService->deleteProductCollection($productCollectionCode);
            DB::commit();
            return redirect()->back()->with('success', $this->title . ': '. $productCollection->product_collection_name .'  Trashed Successfully');
        }catch (\Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }

    }

    /*---------- Managing Products into Product Collection ----- */

    public function showProductAdditionInCollection($productCollectionCode)
    {
        try{

            $productCollection = $this->productCollectionService->findOrFailProductCollectionByCode($productCollectionCode);
           // $brands = $this->brandService->getAllBrands();
            $productsInCollection = $this->productCollectionService->getProductsInCollection($productCollection);
            $addedProductCodes = $productsInCollection->pluck('product_code')->toArray();
            $products = ProductCollectionHelper::getUnAddedProductsInCollection($addedProductCodes);
            return view(Parent::loadViewData($this->module.$this->view.'add-products.create'),compact('productCollection','products','productsInCollection'));

        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }

    }


    public function addProductsToCollection(AddProductstoCollectionRequest $request,$productCollectionCode)
    {
        DB::beginTransaction();
        try{
            $validated = $request->validated();
            $productCodes = $validated['product_codes'];
            $productCollection = $this->productCollectionService->findOrFailProductCollectionByCode($productCollectionCode);
            $productsInCollection = $this->productCollectionService->getProductsInCollection($productCollection);
            $this->productCollectionService->addProductsToCollection($productCollection,$productCodes,$productsInCollection);
            DB::commit();
            return redirect()->back()->with('success', 'Products Added to Collection Succesfully');
        }catch(Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }

    }


    public function removeProductFromCollection($productCollectionCode,$productCode)
    {
        DB::beginTransaction();
        try{

            $this->productCollectionService->removeProductsFromCollection($productCollectionCode,$productCode);
            DB::commit();
            return redirect()->back()->with('success', 'Product Deleted From Collection Succesfully');
        }catch(Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
    public function updateProductCollectionStatus($productCollectionCode)
    {
        try {
            $this->productCollectionService->updateProductCollectionStatus($productCollectionCode);
            return redirect()->back()->with('success', $this->title .' status updated successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
    public function toggleStatus($productCollectionCode,$productCode){
        try{
            $this->productCollectionService->updateActiveStatus($productCollectionCode,$productCode);
            return redirect()->back()->with('success', $this->title .' status updated successfully');
        }catch (Exception $exception){
            return redirect()->route('admin.products.index')->with('danger', $exception->getMessage());
        }
    }
}
