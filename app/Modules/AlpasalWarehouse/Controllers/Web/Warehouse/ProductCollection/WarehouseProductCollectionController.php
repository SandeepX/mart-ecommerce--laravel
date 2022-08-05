<?php

namespace App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\ProductCollection;

use App\Modules\AlpasalWarehouse\Models\WarehouseProductCollection;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use App\Modules\AlpasalWarehouse\Requests\ProductCollection\WarehouseProductCollectionCreateRequest;
use App\Modules\AlpasalWarehouse\Requests\ProductCollection\WarehouseProductCollectionUpdateRequest;
use App\Modules\AlpasalWarehouse\Requests\ProductCollection\AddProductstoCollectionRequest;
use App\Modules\AlpasalWarehouse\Services\ProductCollection\WarehouseProductCollectionService;
use App\Modules\AlpasalWarehouse\Services\WarehouseProductService;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\AlpasalWarehouse\Helpers\WarehouseProductCollectionHelper;
use App\Modules\Brand\Services\BrandService;
use App\Modules\Product\Models\ProductMaster;
use App\Modules\Product\Services\ProductCollection\ProductCollectionService as ProductCollectionProductCollectionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseProductCollectionController extends BaseController
{

    public $title = 'Product Collection';
    public $base_route = 'warehouse.warehouse-product-collections';
    public $sub_icon = 'file';
    public $module = 'AlpasalWarehouse::';


    private $view;
    private $warehouseProductCollectionService;
    private $brandService;
    private $warehouseProductService;


    public function __construct(
         WarehouseProductCollectionService $warehouseProductCollectionService,
        BrandService $brandService,
        WarehouseProductService $warehouseProductService
    )
    {
        $this->middleware('permission:View WH Product Collection List', ['only' => ['index']]);
        $this->middleware('permission:Create WH Product Collection', ['only' => ['create','store']]);
        $this->middleware('permission:Show WH Product Collection', ['only' => ['show']]);
        $this->middleware('permission:Update WH Product Collection', ['only' => ['edit','update']]);
        $this->middleware('permission:Delete WH Product Collection', ['only' => ['destroy']]);
        $this->middleware('permission:Add Products In WH Product Collection',['only'=>['showProductAdditionInCollection','addProductsToCollection','removeProductFromCollection']]);
        $this->middleware('permission:Change WH Product Collection Status', ['only' => ['updateWHProductStatusOfCollection', 'updateWHProductCollectionStatus']]);

        $this->view = 'warehouse.warehouse-product-collections.';
        $this->warehouseProductCollectionService = $warehouseProductCollectionService;
        $this->brandService = $brandService;
        $this->warehouseProductService = $warehouseProductService;


    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
//        dd($this->warehouseProductCollection->products());
//        $coll = WarehouseProductCollection::with('products')->first();
//        dd($coll);
        try{

            $filterParameters = [
                'collection_title' => $request->get('collection_title'),
            ];

            $with =[
                'qualifiedWarehouseProductMasters'
            ];

            // $productCollections= $this->productCollectionService->getAllProductCollections();
            $warehouseproductCollections=WarehouseProductCollectionHelper::filterPaginatedProductCollections($filterParameters,10,$with);
//           dd($warehouseproductCollections);
            return view(Parent::loadViewData($this->module.$this->view.'index'),compact('warehouseproductCollections','filterParameters'));

        }catch (Exception $e){
            return redirect()->route('warehouse.dashboard')->with('danger',$e->getMessage());
        }



    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
//        dd(1);
        return view(Parent::loadViewData($this->module.$this->view.'create'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(WarehouseProductCollectionCreateRequest $request)
    {
        $validated = $request->validated();
        DB::beginTransaction();
        try{
            $warehouseproductCollection =  $this->warehouseProductCollectionService->storeProductCollection($validated);
            DB::commit();
            return redirect()->back()->with('success', $this->title . ': '. $warehouseproductCollection->product_collection_name .'warehouse Product Collection Created Successfully');
        }catch(\Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($warehouseproductCollectionCode)
    {
        try{
            $warehouse_code=getAuthWarehouseCode();
            $warehouseproductCollection = $this->warehouseProductCollectionService->findOrFailWHProductCollectionByCode($warehouse_code,$warehouseproductCollectionCode);
           $warehouseproductCollection->load('warehouseProductMasters.product');
//           dd($warehouseproductCollection);

            return view(Parent::loadViewData($this->module.$this->view.'show'),compact('warehouseproductCollection'));
        }catch(Exception $exception){

            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($warehouseproductCollectionCode)
    {
        try{
            $warehouse_code=getAuthWarehouseCode();
            $warehouseproductCollection = $this->warehouseProductCollectionService->findOrFailWHProductCollectionByCode($warehouse_code,$warehouseproductCollectionCode);
            return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('warehouseproductCollection'));
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(WarehouseProductCollectionUpdateRequest $request, $warehouseproductCollectionCode)
    {
        DB::beginTransaction();
        try{
            $validated = $request->validated();
            $warehouseproductCollection = $this->warehouseProductCollectionService->updateProductCollection($validated, $warehouseproductCollectionCode);

            DB::commit();
            return redirect()->back()->with('success', $this->title . ': '. $warehouseproductCollection->product_collection_name .'  Updated Successfully');

        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($warehouseproductCollectionCode)
    {
        DB::beginTransaction();
        try{
            $warehouseproductCollection = $this->warehouseProductCollectionService->deleteProductCollection($warehouseproductCollectionCode);
            DB::commit();
            return redirect()->back()->with('success', $this->title . ': '. $warehouseproductCollection->product_collection_name .'  Trashed Successfully');
        }catch (\Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }

    }
    /*---------- Managing Products into Product Collection ----- */

    public function showProductAdditionInCollection($warehouseproductCollectionCode)
    {
        try{
            $warehouse_code=getAuthWarehouseCode();
            $warehouseproductCollection = $this->warehouseProductCollectionService->findOrFailWHProductCollectionByCode($warehouse_code,$warehouseproductCollectionCode);
           // $brands = $this->brandService->getAllBrands();
            $productsInCollection = $this->warehouseProductCollectionService->getProductsInCollection($warehouseproductCollection);
            $warehouseproducts = WarehouseProductCollectionHelper::getWHProductsNotAddedInCollection($warehouse_code,$productsInCollection);
            return view(Parent::loadViewData($this->module.$this->view.'add-products.create'),compact('warehouseproductCollection','warehouseproducts','productsInCollection'));

        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }

    }


    public function addProductsToCollection(AddProductstoCollectionRequest $request,$warehouseproductCollectionCode)
    {
        DB::beginTransaction();
        try{
            $warehouse_code=getAuthWarehouseCode();
            $validated = $request->validated();
            $productCodes = $validated['product_codes'];
            $warehouseproductCollection = $this->warehouseProductCollectionService->findOrFailWHProductCollectionByCode($warehouse_code,$warehouseproductCollectionCode);
            $productsInCollection = $this->warehouseProductCollectionService->getProductsInCollection($warehouseproductCollection);
            $this->warehouseProductCollectionService->addProductsToCollection($warehouseproductCollection,$productCodes,$productsInCollection);
            DB::commit();
            return redirect()->back()->with('success', 'Products Added to Collection Succesfully');
        }catch(Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }

    }
    public function removeProductFromCollection($warehouseproductCollectionCode,$productMasterCode)
    {
        DB::beginTransaction();
        try{
            $this->warehouseProductCollectionService->removeProductsFromCollection($warehouseproductCollectionCode,$productMasterCode);
            DB::commit();
            return redirect()->back()->with('success', 'Product Deleted From Collection Succesfully');
        }catch(Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
    public function updateWHProductStatusOfCollection($productCollectionCode,$productMasterCode){
        try{
            $warehouse_code=getAuthWarehouseCode();
            $this->warehouseProductCollectionService->updateActiveStatus($warehouse_code,$productCollectionCode,$productMasterCode);
            return redirect()->back()->with('success', $this->title .' status updated successfully');
        }catch (Exception $exception){
            return redirect()->route('admin.products.index')->with('danger', $exception->getMessage());
        }
    }
    public function updateWHProductCollectionStatus($productCollectionCode)
    {
        try {
            $warehouse_code=getAuthWarehouseCode();
           $this->warehouseProductCollectionService->updateWHProductCollectionStatus($warehouse_code,$productCollectionCode);
            return redirect()->back()->with('success', $this->title .' status updated successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
