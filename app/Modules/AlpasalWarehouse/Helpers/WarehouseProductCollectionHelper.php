<?php


namespace App\Modules\AlpasalWarehouse\Helpers;

use App\Modules\AlpasalWarehouse\Models\WarehouseProductCollection;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use App\Modules\Product\Models\ProductMaster;



class WarehouseProductCollectionHelper
{

    public static function filterPaginatedProductCollections($filterParameters,$paginateBy,$with=[]){
        $warehouseproductCollections = WarehouseProductCollection::with($with)->withCount($with)
            ->when(isset($filterParameters['collection_title']),function ($query) use($filterParameters){
                $query->where('product_collection_title','like','%'.$filterParameters['collection_title'] . '%');
            })->where('warehouse_code',getAuthWarehouseCode());
        $paginateBy = isset($filterParameters['records_per_page'])  ? $filterParameters['records_per_page'] : $paginateBy;

        $warehouseproductCollections= $warehouseproductCollections->latest()->paginate($paginateBy);
        return $warehouseproductCollections;
    }

    public static function getWHProductsOfCollectionWithPagination($whProductCollection,$paginateBy = 8)
    {

            $warehouseProducts = ProductMaster::whereHas('warehouseProducts',function ($query){
                            $query->qualifiedToDisplay();
                        })
                        ->join('warehouse_product_master',
                            'products_master.product_code','=','warehouse_product_master.product_code'
                        )
                         ->where('warehouse_product_master.is_active',1)
                        ->join('wh_product_collection_details', function ($join) use ($whProductCollection) {
                            $join->on('wh_product_collection_details.warehouse_product_master_code', '=', 'warehouse_product_master.warehouse_product_master_code')
        //                ->where('wh_product_collections.warehouse_code', '=',$warehouse_code)
                            // ->where('wh_product_collections.is_active', '=',1)
                            ->where('wh_product_collection_details.product_collection_code', '=',$whProductCollection->product_collection_code)
                            ->where('wh_product_collection_details.is_active', '=',1);
                        })
                ->where('products_master.is_active',1)
                ->whereHas('unitPackagingDetails')
                #->qualifiedToDisplay()
                ->groupBy('warehouse_product_master.product_code')
                ->havingRaw('SUM(warehouse_product_master.current_stock) > 0')
            //->active()
            //->select('warehouse_product_master.product_code','warehouse_product_master.is_active','products_master.product_name','products_master.slug','products_master.highlights','product_images.image','warehouse_product_master.warehouse_code')
            ->paginate($paginateBy);


        return $warehouseProducts;
    }

    public static function getWHProductsNotAddedInCollection($warehouseCode,$productsInCollection)
    {
        $addedProductsCode= $productsInCollection->pluck('product_code')->toArray();
        $warehouseProducts= WarehouseProductMaster::where('warehouse_code',$warehouseCode)
            ->whereNotIn('product_code',$addedProductsCode)
            ->whereHas('product',function ($query){
               $query->where('is_active',1)->whereHas('unitPackagingDetails');
                #$query->qualifiedToDisplay();
            })
            ->qualifiedToDisplay()->latest()->get();
        return $warehouseProducts->unique('product_code');
    }

}
