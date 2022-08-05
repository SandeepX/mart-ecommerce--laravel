<?php


namespace App\Modules\Product\Services\ProductSensitivity;

use App\Modules\Product\Repositories\ProductSensitivity\ProductSensitivityRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductSensitivityService
{
    protected $productSensitivityRepository;

    public function __construct(ProductSensitivityRepository $productSensitivityRepository)
    {
        $this->productSensitivityRepository = $productSensitivityRepository;
    }


    public function getAllProductSensitivities()
    {
        return $this->productSensitivityRepository->getAllProductSensitivities();
    }

    public function findProductSensitivityByID($productSensitivityID)
    {
        return $this->productSensitivityRepository->findProductSensitivityByID($productSensitivityID);
    }

    public function findProductSensitivityByCode($productSensitivityCode)
    {
        return $this->productSensitivityRepository->findProductSensitivityByCode($productSensitivityCode);
    }


    public function findOrFailProductSensitivityByID($productSensitivityID)
    {
        return $this->productSensitivityRepository->findOrFailProductSensitivityByID($productSensitivityID);
    }

    public function findOrFailProductSensitivityByCode($productSensitivityCode)
    {
        return $this->productSensitivityRepository->findOrFailProductSensitivityByCode($productSensitivityCode);
    }

    public function storeProductSensitivity($validated)
    {
        DB::beginTransaction();
        try {

            $productSensitivity = $this->productSensitivityRepository->create($validated);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw($exception);
        }
        return $productSensitivity;
    }

    public function updateProductSensitivity($validated, $productSensitivityCode)
    {
        DB::beginTransaction();

        try {
             $productSensitivity = $this->findOrFailProductSensitivityByCode($productSensitivityCode);
             $this->productSensitivityRepository->update($validated, $productSensitivity);
            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            throw($exception);
        }
        return $productSensitivity;
    }

    public function deleteProductSensitivity($productSensitivityCode)
    {
        DB::beginTransaction();
        try {
            $productSensitivity = $this->findOrFailProductSensitivityByCode($productSensitivityCode);
            $checkDeletion = $productSensitivity->canDelete('products');
            if(!$checkDeletion['can']){
                throw new Exception('Cannot delete sensitivity  as it contains : '. $checkDeletion['relation']);
            }

            $this->productSensitivityRepository->delete($productSensitivity);
            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            throw($exception);
        }
        return $productSensitivity;
    }
}
