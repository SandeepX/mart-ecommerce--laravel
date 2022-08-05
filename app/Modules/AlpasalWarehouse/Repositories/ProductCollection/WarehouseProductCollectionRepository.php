<?php


namespace App\Modules\AlpasalWarehouse\Repositories\ProductCollection;

use App\Modules\AlpasalWarehouse\Helpers\WarehouseProductHelper;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductCollection;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\Product\Models\ProductCollection;
use App\Modules\Product\Models\ProductMaster;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


class WarehouseProductCollectionRepository
{
    use ImageService;

    private $warehouseProductCollection;

    public function __construct(WarehouseProductCollection $warehouseProductCollection)
    {
        $this->warehouseProductCollection = $warehouseProductCollection;
    }
    public function findWHProductCollectionByCode($warehouse_code,$warehouseproductCollectionCode)
    {
        return $this->warehouseProductCollection->where('warehouse_code',$warehouse_code)->where('product_collection_code', $warehouseproductCollectionCode)->first();
    }

    public function findOrFailWHProductCollectionByCode($warehouse_code,$warehouseproductCollectionCode)
    {
        if (!$warehouseproductCollection = $this->findWHProductCollectionByCode($warehouse_code,$warehouseproductCollectionCode)) {
            throw new ModelNotFoundException('No Such Product Collection Found !');
        }
//        dd($warehouseproductCollection);
        return $warehouseproductCollection;
    }
    public function findProductByCode($productCode){
        return WarehouseProductMaster::where('product_code', $productCode)->first();
    }
    public function findWHProductByCode($warehouse_code,$productCollectionCode,$productMasterCode){
        $warehouseProductCollection=WarehouseProductCollection:: join('wh_product_collection_details', function ($join) use ($warehouse_code,$productCollectionCode) {
            $join->on('wh_product_collection_details.product_collection_code', '=', 'wh_product_collections.product_collection_code')
                ->where('wh_product_collection_details.product_collection_code', '=',$productCollectionCode)
                ->where('wh_product_collections.warehouse_code', '=',$warehouse_code);
        })
            ->join('warehouse_product_master', function ($join) use ($productMasterCode) {
                $join->on('warehouse_product_master.warehouse_product_master_code', '=', 'wh_product_collection_details.warehouse_product_master_code')
                    ->where('wh_product_collection_details.warehouse_product_master_code', '=',$productMasterCode);
            })
            ->select('wh_product_collections.product_collection_code','warehouse_product_master.warehouse_product_master_code','wh_product_collection_details.is_active')
              ->first();
        return $warehouseProductCollection;
    }
    public function findOrFailProductByCode($productCode){

        if(!$product = $this->findProductByCode($productCode)){
            throw new ModelNotFoundException('No Such Product Found');
        }
        return $product;
    }

    public function createProductCollection($validated)
    {


        $validated['warehouse_code']= getAuthWarehouseCode();
        $validated['product_collection_image'] = $this->storeImageInServer($validated['product_collection_image'], $this->warehouseProductCollection->uploadFolder);
        $validated['product_collection_slug'] = make_slug($validated['product_collection_title']);
        $validated['is_active'] = 1;
        return $this->warehouseProductCollection->create($validated);
    }

    public function updateProductCollection($validated, $warehouseproductCollection)
    {

        if (isset($validated['product_collection_image'])) {
            $this->deleteImageFromServer($this->warehouseProductCollection->uploadFolder, $warehouseproductCollection->product_collection_image);
            $validated['product_collection_image'] = $this->storeImageInServer($validated['product_collection_image'],$this->warehouseProductCollection->uploadFolder);
        }

        $validated['product_collection_slug'] = make_slug($validated['product_collection_title']);
        $warehouseproductCollection->update($validated);
        return $warehouseproductCollection->fresh();
    }

    public function deleteProductCollection($warehouseproductCollection)
    {
        $warehouseproductCollection->delete();
        return $warehouseproductCollection;
    }
    /*  ------------ Adding Products to Collection -----------------*/



    public function getProductsInCollection($warehouseproductCollection)
    {
        return $warehouseproductCollection->warehouseProductMasters()
            ->groupBy('wh_product_collection_details.warehouse_product_master_code','wh_product_collection_details.product_collection_code')
            ->qualifiedToDisplay()
            ->whereHas('product',function ($query){
                $query->where('is_active',1)->whereHas('unitPackagingDetails');
            })
            ->get();
    }


    public function addProductsToCollection($warehouseproductCollection,array $productCodes,$productsInCollection)
    {
        $productCodeInCollection=[];
        foreach($productsInCollection as $productInCollection)
        {
            $productCodeInCollection[]=$productInCollection->warehouse_product_master_code;
        }
        $result = array_diff($productCodes,$productCodeInCollection);
//        dd($result);
        if(isset($result) && count($result))
        {
            foreach ($result as $i=>$productCode) {
                $singleProductCode = explode(' ', $productCode);
                $result = empty(array_intersect($productCodeInCollection, $singleProductCode));
                if ($result) {
                    $authUserCode = getAuthUserCode();
                    $data = [];
                    $data[$productCode] = [
                        'is_active' => 1,
                        'created_by' => $authUserCode,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                    $warehouseproductCollection->warehouseProductMasters()->syncWithoutDetaching($data);
                }
            }
            return $warehouseproductCollection;
        }
        else{
            throw new \Exception('product already added in collection');
        }


//$result = !empty(array_intersect($productCodeInCollection, $productCodes));
//        if($result)
//        {
//            throw new \Exception('product already added in collection');
//        }
//        $authUserCode = getAuthUserCode();
//        $data = [];
//        foreach ($productCodes as $productCode) {
//            $data[$productCode] = [
//                'is_active' => 1,
//                'created_by' => $authUserCode,
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now(),
//            ];

//        }
//        dd($warehouseproductCollection->products());

//        $warehouseproductCollection->warehouseProductMasters()->syncWithoutDetaching($data);
//
//        return $warehouseproductCollection;
    }


    public function removeProductsFromCollection($warehouseproductCollectionCode,$productMasterCode)
    {
       DB::table('wh_product_collection_details')
            ->where('product_collection_code',$warehouseproductCollectionCode)
            ->where('warehouse_product_master_code',$productMasterCode)
            ->delete();
    }

    public function updateActiveStatus($validated,$productCollectionCode, $productMasterCode){

       DB::table('wh_product_collection_details')
            ->where('product_collection_code',$productCollectionCode)
            ->where('warehouse_product_master_code',$productMasterCode)
            ->update(['is_active' => $validated['is_active']]);
    }

    //Warehouse Product Collecction Api

    public function  getWarehouseProductCollections($warehouse_code)
    {
        $warehouseProductCollections=WarehouseProductCollection::where('warehouse_code',$warehouse_code)
            ->where('is_active',1)
            ->withCount(['warehouseProductMasters'=>function($q){
                $q->where('wh_product_collection_details.is_active',1)
                   # ->where('warehouse_product_master.is_active',1);
                    ->qualifiedToDisplay()
                    ->where('current_stock','>',0)
                     ->whereHas('product',function ($query){
                         $query->where('is_active',1)->whereHas('unitPackagingDetails');
                     });
            }])
            ->having('warehouse_product_masters_count','>',0)
            ->get();
        return $warehouseProductCollections;
    }

    public function  getWarehouseProductCollectionBySlug($product_collection_slug,$warehouse_code)
    {
        $warehouseProductCollection=WarehouseProductCollection::where('product_collection_slug',$product_collection_slug)
            ->where('is_active',1)
            ->where('warehouse_code',$warehouse_code)
            ->withCount(['warehouseProductMasters'=>function($q){
                $q->where('wh_product_collection_details.is_active',1)
                   ->qualifiedToDisplay()
                ->whereHas('product',function ($query){
                    $query->where('is_active',1)->whereHas('unitPackagingDetails');
                });
            }])
            ->first();
        return $warehouseProductCollection;
    }
    public function updateWHProductCollectionActiveStatus($validated,WarehouseProductCollection $warehouseProductCollection){

        $authUserCode = getAuthUserCode();
        // $validated['updated_by'] = $authUserCode;
        $warehouseProductCollection->updated_by=$authUserCode;
        $warehouseProductCollection->is_active=$validated['is_active'];
        $warehouseProductCollection->save();
        return $warehouseProductCollection;
    }
    public function findOrFailWHProductFromCollectionByCode($warehouse_code,$warehouseproductCollectionCode,$productMasterCode)
    {
        return $this->warehouseProductCollection->where('warehouse_code',$warehouse_code)->where('product_collection_code', $warehouseproductCollectionCode)->first();
    }
}
