<?php


namespace App\Modules\AlpasalWarehouse\Repositories\PreOrder;


use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderListing;
use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderProduct;
use Exception;
use Illuminate\Support\Facades\DB;

class WarehousePreOrderProductRepository
{

    public function getPaginatedPreOrderProductsByWarehouseCode($warehousePreOrderListingCode,$warehouseCode,
                                                            $paginateBy,$with=[]){
        return WarehousePreOrderProduct::with($with)
            ->where('warehouse_preorder_listing_code',$warehousePreOrderListingCode)
            ->whereHas('warehousePreOrderListing',function ($query) use($warehouseCode){
                $query->where('warehouse_code',$warehouseCode);
            })->latest()->paginate($paginateBy);
    }

    public function findPreOrderProduct($warehousePreOrderProductCode,$with=[]){

        return WarehousePreOrderProduct::with($with)->where('warehouse_preorder_product_code',$warehousePreOrderProductCode)
            ->first();
    }

    public function findPreOrderProductByProductCode(
        $warehousePreOrderListingCode,$productCode,$productVariantCode,$with=[]){

        return WarehousePreOrderProduct::with($with)->where('warehouse_preorder_listing_code',$warehousePreOrderListingCode)
            ->where('product_code',$productCode)->where('product_variant_code',$productVariantCode)
            ->first();
    }

    public function findOrFailPreOrderProductByPreOrderCode($warehousePreOrderProductCode,$warehousePreOrderListingCode,$with=[]){

        return WarehousePreOrderProduct::with($with)->where('warehouse_preorder_product_code',$warehousePreOrderProductCode)
            ->where('warehouse_preorder_listing_code',$warehousePreOrderListingCode)->firstOrFail();
    }

    public function addProductToWarehousePreOrder(WarehousePreOrderListing $warehousePreOrderListing,$validatedProductsList){

        $warehousePreOrderListing->warehousePreOrderProducts()->createMany($validatedProductsList);
    }

    public function updateOrCreateWarehousePreOrderProduct(WarehousePreOrderListing $warehousePreOrderListing,$validatedProductsList){

        $warehousePreOrderListing->warehousePreOrderProducts()->updateOrCreate(
            [
                'warehouse_preorder_listing_code'=>$warehousePreOrderListing->warehouse_preorder_listing_code,
                'product_code' => $validatedProductsList['product_code'],
                'product_variant_code' => $validatedProductsList['product_variant_code'],
            ],
            $validatedProductsList
        );

    }
    public function updateActiveStatus(WarehousePreOrderProduct $warehousePreOrderProduct,$validated){

        $authUserCode = getAuthUserCode();
        // $validated['updated_by'] = $authUserCode;
        $warehousePreOrderProduct->updated_by=$authUserCode;
        $warehousePreOrderProduct->is_active=$validated['is_active'];
        $warehousePreOrderProduct->save();
        return $warehousePreOrderProduct;
    }

    public function deleteWarehousePreOrderProduct(WarehousePreOrderProduct $warehousePreOrderProduct)
    {
        $warehousePreOrderProduct->delete();
        return $warehousePreOrderProduct;
    }

    public function massDeleteWarehousePreOrderProducts($warehousePreOrderListingCode)
    {
        WarehousePreOrderProduct::where('warehouse_preorder_listing_code',$warehousePreOrderListingCode)
            ->update(['deleted_by' => getAuthUserCode()]);
        WarehousePreOrderProduct::where('warehouse_preorder_listing_code',$warehousePreOrderListingCode)
            ->delete();
    }

    public function cloneWarehouseProductsByListingCode($warehouseCode,$preOrderListingCode,$createdBy){
        $preorderProduts =   DB::select('call preorderProductsFromWarehouse (?,?,?)',array($warehouseCode,$preOrderListingCode,$createdBy));
        return $preorderProduts;
    }

    public function cloneProductsFromSourceToDestinationListingCode($validatedData){
        $preOrderProducts =  DB::select('call  preorderProductsFromSourceToDestination(?,?,?)',
            array($validatedData['source_listing_code'],$validatedData['destination_listing_code'],$validatedData['created_by'])
        );
        return $preOrderProducts;
    }

    public function cloneProductsFromVendorToPreOrderListing($validatedData){

        $preOrderProducts =  DB::select('call  preorderProductsFromVendor(?,?,?)',
            array($validatedData['vendor_code'],$validatedData['preOrderListingCode'],$validatedData['created_by'])
        );

        return $preOrderProducts;
    }

    public function deletePreOrderProductByProductCode($warehousePreOrderListingCode,$preOrderProductCode)
    {
        return WarehousePreOrderProduct::where('warehouse_preorder_listing_code',$warehousePreOrderListingCode)
            ->where('product_code',$preOrderProductCode)
            ->delete();
    }

    public function massDeletePreorderProduct($warehousePreOrderListingCode)
    {
        return WarehousePreOrderProduct::where('warehouse_preorder_listing_code',$warehousePreOrderListingCode)
            ->delete();
    }

    public function changeStatusOfPreOrderproductsByWarehousePreorderListingCode($validatedData)
    {

        return WarehousePreOrderProduct::where('warehouse_preorder_listing_code',$validatedData['warehouse_preorder_listing_code'])
            ->update(['is_active'=> $validatedData['is_active']]);
    }

    public function changeAllWarehousePreorderProductStatusofVendor($warehousPreOrderListingCode,$vendorCode,$status,$with=[]){

        try{
            $preorderProducts =  WarehousePreOrderProduct::with($with)
                ->where('warehouse_preorder_listing_code',$warehousPreOrderListingCode)
                ->whereHas('product.vendor', function ($query) use ($vendorCode) {
                    $query->where('vendors_detail.vendor_code', $vendorCode);
                })
                ->update(['is_active'=>$status]);

            return $preorderProducts;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }

    }

    public function changeStatusOfallVariantsinProduct($warehousePreOrderCode,$productCode,$status){

        try{
            $preorderProducts =  WarehousePreOrderProduct::
                where('warehouse_preorder_listing_code',$warehousePreOrderCode)
                ->where('product_code',$productCode)
                ->update(['is_active'=>$status]);

            return $preorderProducts;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }

    }

    public function getWarehousePreorderProductOrderedByStore($warehousePreOrderListingCode,$preOrderProductCode)
    {
       $storePreorderProductDetail =  DB::table('warehouse_preorder_products')
            ->join('store_preorder_details', 'warehouse_preorder_products.warehouse_preorder_product_code', '=', 'store_preorder_details.warehouse_preorder_product_code')
            ->where('warehouse_preorder_products.warehouse_preorder_listing_code',$warehousePreOrderListingCode)
            ->where('warehouse_preorder_products.product_code',$preOrderProductCode)
            ->where('store_preorder_details.deleted_at',null)
            ->where('warehouse_preorder_products.deleted_at',null)
            ->select('store_preorder_details.warehouse_preorder_product_code')
            ->count();
       return $storePreorderProductDetail;
    }

//    public function getWarehousePreorderProductByWarehouseListingCodeAndProductCode($warehousPreOrderListingCode,$productCode,$select=[])
//    {
//        return WarehousePreOrderProduct::select($select)
//            ->where('warehouse_preorder_listing_code',$warehousPreOrderListingCode)
//            ->where('product_code',$productCode)
//            ->get();
//    }
}
