<?php


namespace App\Modules\AlpasalWarehouse\Services;

use App\Modules\AlpasalWarehouse\Repositories\WarehouseProductMasterRepository;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseProductPriceRepository;
use App\Modules\Product\Helpers\ProductPriceHelper;
use App\Modules\Product\Models\ProductUnitPackageDetail;
use App\Modules\Product\Repositories\ProductRepository;
use App\Modules\Product\Repositories\ProductVariantRepository;
use Exception;
use Illuminate\Support\Facades\DB;
class WarehouseProductService
{

    private $warehouseProductMasterRepository,$warehouseProductPriceRepository;
    private $productRepository;
    private $productVariantRepository;

    public function __construct(WarehouseProductMasterRepository $warehouseProductMasterRepository,
                                WarehouseProductPriceRepository $warehouseProductPriceRepository,
                                ProductRepository $productRepository,
                                ProductVariantRepository $productVariantRepository
    ){
        $this->warehouseProductMasterRepository = $warehouseProductMasterRepository;
        $this->warehouseProductPriceRepository=$warehouseProductPriceRepository;
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
    }

    public function findOrFailProductByWarehouseCode($warehouseCode,$productCode,$productVariantCode=null){

        try{
            $warehouseProduct = $this->warehouseProductMasterRepository->findOrFailProductByWarehouseCode($warehouseCode,
                $productCode,$productVariantCode);
            return $warehouseProduct;
        }catch (Exception $exception){
            throw $exception;
        }

    }

    public function findOrFailWarehouseProductWithCodeAndWarehouseCode($warehouseProductMasterCode,$warehouseCode,$with=[]){
        try{
            $warehouseProduct = $this->warehouseProductMasterRepository->findOrFailProductByCode(
                $warehouseProductMasterCode,$warehouseCode,$with);
            return $warehouseProduct;
        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function findOrFailQualifiedProductBySlugWith($warehouseCode,$productSlug,array $with){

        try{
            $warehouseProduct = $this->warehouseProductMasterRepository->findOrFailQualifiedProductBySlug($warehouseCode,
                $productSlug,$with);
            return $warehouseProduct;
        }catch (Exception $exception){
            throw $exception;
        }

    }

    public function getWarehouseProductDetail($productCode,$warehouseCode){
        try{
            $with=[
                'product',
                'product.package.packageType',
                'product.vendor',
                'product.brand',
                'product.category',
                'productVariant',
                'warehouseProductPriceMaster'
            ];
            $hasProductVariants = false;
            $warehouseProduct = $this->warehouseProductMasterRepository->findOrFailProductByProductCode($productCode,
                $warehouseCode,$with);


            $productVariants= $this->warehouseProductMasterRepository->getProductVariants($warehouseCode,$warehouseProduct->product_code);

            if (count($productVariants) > 0){
                $hasProductVariants=true;
            }
            $warehouseProduct['product_variants']=$productVariants;
            $warehouseProduct['has_product_variants']=$hasProductVariants;
            ///$warehouseProduct->setAttribute('product_variants', $productVariants);
            return $warehouseProduct;
        }catch (Exception $exception){
            throw $exception;
        }

    }

    public function toggleWPMStatus($wpmCode)
    {
        return $this->warehouseProductMasterRepository->toggleWPMStatus($wpmCode);
    }

    public function changeProductStatus($validatedData)
    {
        DB::beginTransaction();
        try{
            $individualProductStatus = $this->warehouseProductMasterRepository->ChangeProductStatus($validatedData);
            DB::commit();
            return $individualProductStatus;
        }catch(Exception $exception){
            DB::rollBack();
            return $exception;
        }
    }

    public function changeAllWarehouseProductStatus($changeStatusTo)
    {
        DB::beginTransaction();
        try{
            $warehouseProductStatus = $this->warehouseProductMasterRepository->warehouseAllProductStatusChange($changeStatusTo);
            DB::commit();
            return $warehouseProductStatus;
        }catch(Exception $exception){
            DB::rollBack();
            return $exception;
        }
    }

    public function setWarehouseProductOrderLimit($validatedData)
    {
        try{
            return $this->warehouseProductMasterRepository->setWarehouseProductQtyOrderLimit($validatedData);

        }catch(Exception $exception){
            return $exception;
        }
    }

    public function updatePriceSettingOfWarehouseProduct($validatedData,$warehouseProductMasterCode){
        try{
            $warehouseCode = getAuthWarehouseCode();
            $with=['warehouseProductPriceMaster','warehouseProductPriceHistories'];
            $warehouseProduct = $this->warehouseProductMasterRepository->findOrFailProductByCode($warehouseProductMasterCode,
                $warehouseCode,$with);

            //temporary upper package price implementation in service
            $productCode = $warehouseProduct->product_code;
            $productVariantCode = $warehouseProduct->product_variant_code;

            $productPackagingDetail = ProductUnitPackageDetail::where('product_code',$productCode)
                ->where('product_variant_code',$productVariantCode)->first();
            if (!$productPackagingDetail){
                throw new Exception('Product packaging detail not found for product '. $productCode);
            }

            $marginValues = [];
            $marginValues = [
                'admin_margin_type' => $validatedData['admin_margin_type'],
                'admin_margin_value' => $validatedData['admin_margin_value'] ,
                'wholesale_margin_type' => $validatedData['wholesale_margin_type'],
                'wholesale_margin_value' => $validatedData['wholesale_margin_value'],
                'retail_store_margin_type' => $validatedData['retail_margin_type'],
                'retail_store_margin_value' => $validatedData['retail_margin_value']
            ];

            $productName =  $this->productRepository->findOrFailProductByCode($productCode)
                ->product_name;
            if($productVariantCode){
                $variantName = $this->productVariantRepository->findOrFailByProductCodeAndVariantCode(
                    $productCode, $productVariantCode
                )->product_variant_name;
            }

            if(!ProductPriceHelper::checkNegativeProductPrice($validatedData['mrp'],$marginValues)){
                throw new Exception('Margin Value For Product: '.$productName.'
                    '.( isset($variantName) ? $variantName : '') .' exceeds than MRP. Cannot add Negative Price');
            }

            if ($productPackagingDetail->macro_to_super_value){
                $microValue=$productPackagingDetail->macro_to_super_value * $productPackagingDetail->unit_to_macro_value *$productPackagingDetail->micro_to_unit_value;
                $validatedData['mrp'] = $validatedData['mrp'] /$microValue;
            }elseif ($productPackagingDetail->unit_to_macro_value){
                $validatedData['mrp'] = $validatedData['mrp'] /($productPackagingDetail ->unit_to_macro_value *$productPackagingDetail ->micro_to_unit_value);
            }
            elseif ($productPackagingDetail->micro_to_unit_value){
                $validatedData['mrp'] = $validatedData['mrp'] /$productPackagingDetail ->micro_to_unit_value;
            }else{
                $validatedData['mrp'] = $validatedData['mrp'];
            }

           // dd($validatedData);
            //end of temp implementation
            DB::beginTransaction();

            $warehouseProductPrice = $this->warehouseProductPriceRepository->updateProductPrice($warehouseProduct,$validatedData);
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }

    }

    public function getWarehousesProductDetail($productCode){
        try{
            $with=['product','productVariant','warehouse','warehouseProductStockView'];
            $warehouseProductMasters = $this->warehouseProductMasterRepository
                ->paginateProductsByProductCode($productCode, 20,$with);

            return $warehouseProductMasters;
        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function storeMassPriceSettingOfProduct($validatedData,$warehouseCode,$productCode){

        try {
            $warehouseProduct = $this->warehouseProductMasterRepository->findOrFailProductByProductCode($productCode,
                $warehouseCode,['product']);


            $productVariants= $this->warehouseProductMasterRepository->getProductVariants($warehouseCode,$warehouseProduct->product_code);
            $productVariantsCode = $productVariants->pluck('product_variant_code')->toArray();
            $toBeStoredData = [];
            //dd($validatedData['mrp']);

            DB::beginTransaction();
            foreach (array_filter($validatedData['mrp']) as $key => $mrp) {
                if (count($productVariantsCode) > 0) {
                    if (!in_array($validatedData['product_variant_code'][$key], $productVariantsCode)) {
                        throw new Exception('Variant not found for the product');
                    }
                }

                $warehouseProductMaster = $this->warehouseProductMasterRepository->findOrFailProductByWarehouseCode($warehouseCode,$productCode,$validatedData['product_variant_code'][$key]);

               $data = [
                    'mrp' => $mrp,
                    'admin_margin_type' => $validatedData['admin_margin_type'][$key],
                    'admin_margin_value' => $validatedData['admin_margin_value'][$key],
                    'wholesale_margin_type' => $validatedData['wholesale_margin_type'][$key],
                    'wholesale_margin_value' => $validatedData['wholesale_margin_value'][$key],
                    'retail_margin_type' => $validatedData['retail_margin_type'][$key],
                    'retail_margin_value' => $validatedData['retail_margin_value'][$key],
                ];

               $this->updatePriceSettingOfWarehouseProduct($data,$warehouseProductMaster->warehouse_product_master_code);
            }

            DB::commit();

            return $warehouseProduct;

        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }
}
