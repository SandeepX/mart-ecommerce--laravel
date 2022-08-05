<?php


namespace App\Modules\AlpasalWarehouse\Services;


use App\Modules\AlpasalWarehouse\Helpers\PreOrder\WarehousePreOrderHelper;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductPackagingUnitDisableList;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseProductMasterRepository;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseProductPackagingDisableListRepo;
use App\Modules\Product\Models\ProductUnitPackageDetail;
use Illuminate\Support\Facades\DB;

use Exception;
class WarehouseProductPackagingService
{
    private $warehouseProductMasterRepository,$warehouseProductPackagingDisableListRepo;

    public function __construct(WarehouseProductMasterRepository $warehouseProductMasterRepository,
                                WarehouseProductPackagingDisableListRepo $warehouseProductPackagingDisableListRepo){
        $this->warehouseProductMasterRepository = $warehouseProductMasterRepository;
        $this->warehouseProductPackagingDisableListRepo = $warehouseProductPackagingDisableListRepo;

    }

    public function disableProductsPackagingForPreOrder($validatedData, $productCode)
    {

        try {
            $authWarehouseCode = getAuthWarehouseCode();

            $warehouseProduct = $this->warehouseProductMasterRepository->findOrFailProductByProductCode($productCode,
                $authWarehouseCode,['product']);

            $productVariants= $this->warehouseProductMasterRepository->getProductVariants(
                $authWarehouseCode,$warehouseProduct->product_code);
            $productVariantsCode = $productVariants->pluck('product_variant_code')->toArray();

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

                $warehouseProductMaster = $this->warehouseProductMasterRepository->findOrFailProductByWarehouseCode(
                    $authWarehouseCode,$productCode,$validatedData['product_variant_code'][$key]);

               $toBeDeletedProductPackageDisableList[$key]= $warehouseProductMaster->warehouse_product_master_code;

                $productPackagingDetail = ProductUnitPackageDetail::where('product_code',$productCode)
                    ->where('product_variant_code',$productVariantCode)->first();
                if (!$productPackagingDetail){
                    throw new Exception('Product packaging detail not found for product '. $productCode);
                }

                if ($microUnitCode == 1){
                    array_push($toBeStoredData, [
                        'warehouse_product_master_code' => $warehouseProductMaster->warehouse_product_master_code,
                        'unit_name' => 'micro',
                        'disabled_by' => getAuthUserCode()
                    ]);
                }
                if ($validatedData['unit_code'][$key] == 1){
                    array_push($toBeStoredData, [
                         'warehouse_product_master_code' => $warehouseProductMaster->warehouse_product_master_code,
                        'unit_name' => 'unit',
                        'disabled_by' => getAuthUserCode()
                    ]);
                }

                if ($validatedData['macro_unit_code'][$key] == 1){
                    array_push($toBeStoredData, [
                         'warehouse_product_master_code' => $warehouseProductMaster->warehouse_product_master_code,
                        'unit_name' => 'macro',
                        'disabled_by' => getAuthUserCode()
                    ]);
                }

                if ($validatedData['super_unit_code'][$key] == 1){
                    array_push($toBeStoredData, [
                         'warehouse_product_master_code' => $warehouseProductMaster->warehouse_product_master_code,
                        'unit_name' => 'super',
                        'disabled_by' => getAuthUserCode()
                    ]);
                }

            }
             //dd($toBeStoredData);
            DB::beginTransaction();
            $this->warehouseProductPackagingDisableListRepo->massDeleteUnitDisableListByPreOrderProductCode($toBeDeletedProductPackageDisableList);
            foreach ($toBeStoredData as $data){
                $this->warehouseProductPackagingDisableListRepo->saveUnitDisableList($data);
            }
            DB::commit();
           // return $warehousePreOrder;

        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function disableMassWarehousePreOrderProductsMicroPackaging($warehouseCode,$validatedData){
        try{

            $warehouseProductsMasterCode = WarehouseProductMaster::where('warehouse_code',$warehouseCode)
                ->pluck('warehouse_product_master_code')->toArray();
            DB::beginTransaction();
            if ($validatedData['packaging_status'] == 0){

                //for disabling micro packaging
                foreach ($warehouseProductsMasterCode as $wpmCode){

                    WarehouseProductPackagingUnitDisableList::updateOrCreate(
                        [
                            'warehouse_product_master_code' => $wpmCode,
                            'unit_name' => 'micro'
                        ],
                        [
                            'disabled_by' => getAuthUserCode()
                        ]);
                }

            }
            else{
                WarehouseProductPackagingUnitDisableList::whereIn('warehouse_product_master_code',$warehouseProductsMasterCode)
                    ->where('unit_name','micro')->delete();
            }

            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }
}
