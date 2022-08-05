<?php


namespace App\Modules\Product\Helpers;

use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderProduct;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductPriceMaster;
use App\Modules\Store\Helpers\StoreWarehouseHelper;
use App\Modules\Vendor\Models\ProductPriceList;

use Exception;

class ProductPriceHelper
{
    //price range for store user
    public function getProductStorePriceRange($productCode)
    {

        if ((auth('api')->check()) && auth('api')->user()->isStoreUser()) {
            $warehouseCode = StoreWarehouseHelper::getFirstActiveWarehouseCodeAssociatedWithStore(getAuthGuardStoreCode());
            //$productPriceLists = ProductPriceList::where('product_code',$productCode)->get();
            $productPriceLists = WarehouseProductPriceMaster::whereHas('warehouseProductMaster', function ($query) use ($warehouseCode, $productCode) {
                $query->where('warehouse_product_master.warehouse_code', $warehouseCode)->where('warehouse_product_master.product_code', $productCode);
            })->get();

            if (count($productPriceLists) == 0) {
                return 'Rs. N/A';
            }

            $priceRanges = [];

            foreach ($productPriceLists as $priceList) {
                $storePrice = $this->calculateStoreProductPrice($priceList);
                array_push($priceRanges, $storePrice);
            }


            return (count($priceRanges) == 1)
                ? 'Rs.' . roundPrice($priceRanges[0])
                : 'Rs.' . roundPrice(min($priceRanges)) . ' to ' . 'Rs.' . roundPrice(max($priceRanges));

        }


        return 'N/A';
    }

    //product price for store
    public function getProductStorePrice($warehouseCode, $productCode, $productVariantCode = null)
    {
        //$priceList = $this->getProductPriceList($productCode, $productVariantCode);
        $price = $this->getWarehouseProductPrice($warehouseCode, $productCode, $productVariantCode);
        if ($price) {
            return $this->calculateStoreProductPrice($price);
        }
        return 'N/A';

    }

    public function getProductAuthStorePrice($productCode, $productVariantCode = null)
    {
        $warehouseCode = StoreWarehouseHelper::getFirstActiveWarehouseCodeAssociatedWithStore(getAuthGuardStoreCode());

        //$priceList = $this->getProductPriceList($productCode, $productVariantCode);
        $price = $this->getWarehouseProductPrice($warehouseCode, $productCode, $productVariantCode);
        if ($price) {
            return $this->calculateStoreProductPrice($price);
        }
        return 'N/A';

    }

    //pre order product price range for store user
    public function getPreOrderProductStorePriceRange($warehousePreOrderListingCode, $productCode)
    {

        if ((auth('api')->check()) && auth('api')->user()->isStoreUser()) {
            $warehouseCode = StoreWarehouseHelper::getFirstActiveWarehouseCodeAssociatedWithStore(getAuthGuardStoreCode());
            //$productPriceLists = ProductPriceList::where('product_code',$productCode)->get();
            $productPriceLists = WarehousePreOrderProduct::whereHas('warehousePreOrderListing', function ($query) use ($warehouseCode) {
                $query->where('warehouse_preorder_listings.warehouse_code', $warehouseCode);
            })->where('warehouse_preorder_products.product_code', $productCode)
                ->where('warehouse_preorder_products.warehouse_preorder_listing_code', $warehousePreOrderListingCode)->get();

            if (count($productPriceLists) == 0) {
                return 'Rs. N/A';
            }

            $priceRanges = [];

            foreach ($productPriceLists as $priceList) {
                $storePrice = $this->calculateStoreProductPrice($priceList);
                array_push($priceRanges, $storePrice);
            }


            return (count($priceRanges) == 1)
                ? 'Rs.' . roundPrice($priceRanges[0])
                : 'Rs.' . roundPrice(min($priceRanges)) . ' to ' . 'Rs.' . roundPrice(max($priceRanges));

        }


        return 'N/A';
    }

    public function getPreOrderProductStorePrice(
        $warehouseCode,
        $warehousePreOrderOrderListingCode,
        $productCode,
        $productVariantCode = null
    )
    {
        $price = $this->getWarehousePreOrderProductPrice(
            $warehouseCode,
            $warehousePreOrderOrderListingCode,
            $productCode,
            $productVariantCode
        );


        if ($price) {
            return $this->calculateStoreProductPrice($price);
        }
        return 'N/A';

    }

    public function findPreOrderProductStorePrice(
        $warehouseCode,
        $warehousePreOrderOrderListingCode,
        $productCode,
        $productVariantCode = null
    )
    {
        $price = $this->getWarehousePreOrderProductPrice(
            $warehouseCode,
            $warehousePreOrderOrderListingCode,
            $productCode,
            $productVariantCode
        );


        if ($price) {
            return ($this->calculateStoreProductPrice($price));
        }

    }


    public function getWarehousePreOrderProductPrice($warehouseCode, $warehousePreOrderListingCode, $productCode, $productVariantCode = null)
    {

        return WarehousePreOrderProduct::whereHas('warehousePreOrderListing', function ($query) use ($warehouseCode) {
            $query->where('warehouse_preorder_listings.warehouse_code', $warehouseCode);
        })->where('warehouse_preorder_products.product_code', $productCode)
            ->where('warehouse_preorder_products.warehouse_preorder_listing_code', $warehousePreOrderListingCode)
            ->where('warehouse_preorder_products.product_variant_code', $productVariantCode)
            ->first();
    }

    //product price for warehouse :usage:warehouse purchase order
    public function getProductWarehousePrice($productCode, $productVariantCode = null)
    {
        $priceList = $this->getProductPriceList($productCode, $productVariantCode);
        if ($priceList) {
            return $this->calculateWarehouseProductPrice($priceList);
        }
        return 'N/A';

    }

    public function getNewProductWarehousePrice($productCode, $productVariantCode = null)
    {
        $priceList = $this->getProductPriceList($productCode, $productVariantCode);
        if ($priceList) {
            return $this->calculateWarehouseProductPrice($priceList);
        }
        return null;

    }

    //seen by store
    public function getWarehouseProductPrice($warehouseCode, $productCode, $productVariantCode = null)
    {
        /* return ProductPriceList::where('product_code', $productCode)
             ->where('product_variant_code', $productVariantCode)->first();*/

        return WarehouseProductPriceMaster::whereHas('warehouseProductMaster', function ($query) use ($warehouseCode, $productCode, $productVariantCode) {
            $query->where('warehouse_product_master.warehouse_code', $warehouseCode)
                ->where('warehouse_product_master.product_code', $productCode)
                ->where('product_variant_code', $productVariantCode);
        })->first();
    }


    public function getProductPriceList($productCode, $productVariantCode = null)
    {
        return ProductPriceList::where('product_code', $productCode)
            ->where('product_variant_code', $productVariantCode)->first();
    }

    public function findOrFailProductPriceList($productCode, $productVariantCode = null)
    {
        $productPrice = ProductPriceList::where('product_code', $productCode)
            ->where('product_variant_code', $productVariantCode)->first();

        if (!$productPrice) {
            throw new Exception('Price not found for product ' . $productCode);
        }

        return $productPrice;
    }

    // warehouse set : warehouse_product_price_master
    public function calculateStoreProductPrice($priceList)
    {
        $mrp = $priceList->mrp;

        $wholesaleMarginValue = $this->marginValueCalculationFromMarginType(
            $priceList->wholesale_margin_type,
            $priceList->wholesale_margin_value,
            $mrp
        );

        $retailStoreMarginValue = $this->marginValueCalculationFromMarginType(
            $priceList->retail_margin_type,
            $priceList->retail_margin_value,
            $mrp
        );

        return $mrp - $wholesaleMarginValue - $retailStoreMarginValue;
    }

    // vendor set product price lists tbl
    public function calculateWarehouseProductPrice($priceList)
    {
        $mrp = $priceList->mrp;

        $adminMarginValue = $this->marginValueCalculationFromMarginType(
            $priceList->admin_margin_type,
            $priceList->admin_margin_value,
            $mrp
        );


        $wholesaleMarginValue = $this->marginValueCalculationFromMarginType(
            $priceList->wholesale_margin_type,
            $priceList->wholesale_margin_value,
            $mrp
        );

        $retailStoreMarginValue = $this->marginValueCalculationFromMarginType(
            $priceList->retail_store_margin_type,
            $priceList->retail_store_margin_value,
            $mrp
        );

        return $mrp - $adminMarginValue - $wholesaleMarginValue - $retailStoreMarginValue;
    }


    public function marginValueCalculationFromMarginType($marginType, $marginValue, $mrp)
    {
        $marginValue = ($marginType == 'p') ? (($marginValue / 100) * $mrp) : $marginValue;
        return $marginValue;
    }

    public static function checkNegativeProductPrice($mrp,$marginValues){

        $adminMarginValue = 0;
        if($marginValues['admin_margin_type'] == 'f'){
            $adminMarginValue = $marginValues['admin_margin_value'];
        }elseif($marginValues['admin_margin_type'] == 'p'){
            $adminMarginValue = $marginValues['admin_margin_value']/100 * $mrp;
        }

        $warehouseMarginValue = 0;
        if($marginValues['wholesale_margin_type'] == 'f'){
            $warehouseMarginValue = $marginValues['wholesale_margin_value'];
        }elseif($marginValues['wholesale_margin_type'] == 'p'){
            $warehouseMarginValue = $marginValues['wholesale_margin_value']/100 * $mrp;
        }

        $retailMarginValue = 0;
        if($marginValues['retail_store_margin_type'] == 'f'){
            $retailMarginValue = $marginValues['retail_store_margin_value'];
        }elseif($marginValues['retail_store_margin_type'] == 'p'){
            $retailMarginValue = $marginValues['retail_store_margin_value']/100 * $mrp;
        }


        if(($adminMarginValue + $warehouseMarginValue + $retailMarginValue) >= $mrp){
          return false;
        }
        return true;
    }
}
