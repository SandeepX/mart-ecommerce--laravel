<?php


namespace App\Modules\Store\Helpers\PreOrder;


use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderProduct;

class StorePreOrderCollectionHelper
{

    public static function preOrderProductsForStoreCollection($warehousePreOrderListingCode,$with=[]){

        $preOrderProducts= WarehousePreOrderProduct::with($with)
                ->where('warehouse_preorder_products.warehouse_preorder_listing_code',$warehousePreOrderListingCode)
                ->where('is_active',1);

        $preOrderProducts= $preOrderProducts->select(
            'warehouse_preorder_products.warehouse_preorder_product_code',
                    'warehouse_preorder_products.warehouse_preorder_listing_code',
                    'warehouse_preorder_products.product_code',
                    'warehouse_preorder_products.product_variant_code'
        );

        $preOrderProducts= $preOrderProducts->groupBy('warehouse_preorder_products.product_code')
            ->orderBy('warehouse_preorder_products.created_at','DESC')
            ->take(8)
            ->get();

        return $preOrderProducts;
    }

}
