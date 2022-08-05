<?php


namespace App\Modules\AlpasalWarehouse\Services\PreOrder;


use App\Modules\AlpasalWarehouse\Helpers\PreOrder\WarehousePreOrderHelper;
use App\Modules\AlpasalWarehouse\Models\PreOrder\PreOrderPackagingUnitDisableList;
use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderProduct;
use App\Modules\AlpasalWarehouse\Repositories\PreOrder\WarehousePreOrderProductPackagingDisableListRepo;
use App\Modules\AlpasalWarehouse\Repositories\PreOrder\WarehousePreOrderProductRepository;
use App\Modules\AlpasalWarehouse\Repositories\PreOrder\WarehousePreOrderRepository;
use App\Modules\Product\Models\ProductUnitPackageDetail;
use App\Modules\Product\Repositories\ProductRepository;
use App\Modules\Store\Repositories\BalanceManagement\StoreBalanceManagementRepository;
use App\Modules\Store\Repositories\PreOrder\StorePreOrderRepository;
use App\Modules\Store\Repositories\PreOrder\StorePreOrderStatusLogRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class WarehousePreOrderProductPackagingService
{
    private $warehousePreOrderRepository, $productRepository, $warehousePreOrderProductRepository;
    private $warehousePreOrderProductPackagingDisableListRepo;

    public function __construct(WarehousePreOrderRepository $warehousePreOrderRepository,
                                ProductRepository $productRepository,
                                WarehousePreOrderProductRepository $warehousePreOrderProductRepository,
                                WarehousePreOrderProductPackagingDisableListRepo $warehousePreOrderProductPackagingDisableListRepo
                                )
    {
        $this->warehousePreOrderRepository = $warehousePreOrderRepository;
        $this->productRepository = $productRepository;
        $this->warehousePreOrderProductRepository = $warehousePreOrderProductRepository;
        $this->warehousePreOrderProductPackagingDisableListRepo = $warehousePreOrderProductPackagingDisableListRepo;
    }

    public function disableProductsPackagingForPreOrder($validatedData, $warehousePreOrderCode, $productCode)
    {
        try {
            $authWarehouseCode = getAuthWarehouseCode();

            $warehousePreOrder = $this->warehousePreOrderRepository->findOrFailPreOrderByWarehouseCode($warehousePreOrderCode, $authWarehouseCode);

            if ($warehousePreOrder->isPastFinalizationTime()){
                throw new Exception('Cannot add product after finalization time.');
            }

            if ($warehousePreOrder->isCancelled()){
                throw new Exception('Cannot add product: pre-order was cancelled.');
            }
            if (!WarehousePreOrderHelper::doesPreOrderConsistProduct($warehousePreOrderCode, $productCode)) {
                throw new Exception('Preorder does not consist the product.');
            }
            $product = $this->productRepository->findOrFailProductByCodeWith($productCode, ['productVariants']);
            $productVariantsCode = $product->productVariants->pluck('product_variant_code')->toArray();
            $toBeStoredData = [];
            $toBeDeletedProductPackageDisableList=[];
           // dd($validatedData);
            foreach ($validatedData['micro_unit_code'] as $key => $microUnitCode) {
                $productVariantCode=null;
                if (count($productVariantsCode) > 0) {
                    $productVariantCode=$validatedData['product_variant_code'][$key];
                    if (!in_array($productVariantCode, $productVariantsCode)) {
                        throw new Exception('Variant not found for the product');
                    }
                }
                $warehousePreOrderProduct = $this->warehousePreOrderProductRepository->findPreOrderProductByProductCode(
                    $warehousePreOrder->warehouse_preorder_listing_code,$productCode,$productVariantCode);

                $toBeDeletedProductPackageDisableList[$key]= $warehousePreOrderProduct->warehouse_preorder_product_code;

                $productPackagingDetail = ProductUnitPackageDetail::where('product_code',$productCode)
                    ->where('product_variant_code',$productVariantCode)->first();
                if (!$productPackagingDetail){
                    throw new Exception('Product packaging detail not found for product '. $productCode);
                }

                if ($microUnitCode == 1){
                    array_push($toBeStoredData, [
                        'warehouse_preorder_product_code' => $warehousePreOrderProduct->warehouse_preorder_product_code,
                        'unit_name' => 'micro',
                        'disabled_by' => getAuthUserCode()
                    ]);
                }
                if ($validatedData['unit_code'][$key] == 1){
                    array_push($toBeStoredData, [
                        'warehouse_preorder_product_code' => $warehousePreOrderProduct->warehouse_preorder_product_code,
                        'unit_name' => 'unit',
                        'disabled_by' => getAuthUserCode()
                    ]);
                }

                if ($validatedData['macro_unit_code'][$key] == 1){
                    array_push($toBeStoredData, [
                        'warehouse_preorder_product_code' => $warehousePreOrderProduct->warehouse_preorder_product_code,
                        'unit_name' => 'macro',
                        'disabled_by' => getAuthUserCode()
                    ]);
                }

                if ($validatedData['super_unit_code'][$key] == 1){
                    array_push($toBeStoredData, [
                        'warehouse_preorder_product_code' => $warehousePreOrderProduct->warehouse_preorder_product_code,
                        'unit_name' => 'super',
                        'disabled_by' => getAuthUserCode()
                    ]);
                }

            }
           // dd($toBeStoredData);
            DB::beginTransaction();
            $this->warehousePreOrderProductPackagingDisableListRepo->massDeleteUnitDisableListByPreOrderProductCode($toBeDeletedProductPackageDisableList);
            foreach ($toBeStoredData as $data){
                $this->warehousePreOrderProductPackagingDisableListRepo->saveUnitDisableList($data);
            }

           // $this->warehousePreOrderProductRepository->addProductToWarehousePreOrder($warehousePreOrder, $toBeStoredData);
            DB::commit();
            return $warehousePreOrder;

        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function disableMassWarehousePreOrderProductsMicroPackaging($validatedData,$warehousePreOrderCode){
        try{
            $authWarehouseCode = getAuthWarehouseCode();
         //   dd($warehousePreOrderCode);

            $warehousePreOrder = $this->warehousePreOrderRepository->findOrFailPreOrderByWarehouseCode($warehousePreOrderCode, $authWarehouseCode);

            if ($warehousePreOrder->isPastFinalizationTime()){
                throw new Exception('Cannot add product after finalization time.');
            }

            if ($warehousePreOrder->isCancelled()){
                throw new Exception('Cannot add product: pre-order was cancelled.');
            }
            $warehousePreOrderProductsCode = WarehousePreOrderProduct::where('warehouse_preorder_listing_code',$warehousePreOrder->warehouse_preorder_listing_code)
                ->pluck('warehouse_preorder_product_code')->toArray();
            DB::beginTransaction();
            if ($validatedData['packaging_status'] == 0){

                //for disabling micro packaging
                foreach ($warehousePreOrderProductsCode as $warehousePreOrderProductCode){

                    PreOrderPackagingUnitDisableList::updateOrCreate(
                        [
                            'warehouse_preorder_product_code' => $warehousePreOrderProductCode,
                            'unit_name' => 'micro'
                        ],
                        [
                            'disabled_by' => getAuthUserCode()
                        ]);
                }

            }
            else{
                PreOrderPackagingUnitDisableList::whereIn('warehouse_preorder_product_code',$warehousePreOrderProductsCode)
                    ->where('unit_name','micro')->delete();
            }

            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }

    }
}
