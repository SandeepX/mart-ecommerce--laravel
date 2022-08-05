<?php

namespace App\Modules\Product\Services\ProductWarranty;

use App\Modules\Product\Repositories\ProductWarranty\ProductWarrantyRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductWarrantyService
{
    protected $productWarrantyRepository;

    public function __construct(ProductWarrantyRepository $productWarrantyRepository)
    {
        $this->productWarrantyRepository = $productWarrantyRepository;
    }


    public function getAllProductWarranties()
    {
        return $this->productWarrantyRepository->getAllProductWarranties();
    }

    public function findProductWarrantyByID($productWarrantyID)
    {
        return $this->productWarrantyRepository->findProductWarrantyByID($productWarrantyID);
    }

    public function findProductWarrantyByCode($productWarrantyCode)
    {
        return $this->productWarrantyRepository->findProductWarrantyByCode($productWarrantyCode);
    }

    public function findOrFailProductWarrantyByID($productWarrantyID)
    {
        return $this->productWarrantyRepository->findOrFailProductWarrantyByID($productWarrantyID);
    }

    public function findOrFailProductWarrantyByCode($productWarrantyCode)
    {
        return $this->productWarrantyRepository->findOrFailProductWarrantyByCode($productWarrantyCode);
    }

    public function storeProductWarranty($validated)
    {
        DB::beginTransaction();
        try {

            $productWarranty = $this->productWarrantyRepository->create($validated);
            DB::commit();
            return $productWarranty;

        } catch (Exception $exception) {
            DB::rollBack();
            throw($exception);

        }
    }

    public function updateProductWarranty($validated, $productWarrantyCode)
    {
        DB::beginTransaction();

        try {
            $productWarranty = $this->findOrFailProductWarrantyByCode($productWarrantyCode);
            $this->productWarrantyRepository->update($validated, $productWarranty);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw($exception);
        }
        return $productWarranty;
    }

    public function deleteProductWarranty($productWarrantyCode)
    {
        DB::beginTransaction();
        try {
            $productWarranty = $this->findOrFailProductWarrantyByCode($productWarrantyCode);
            $checkDeletion = $productWarranty->canDelete('productWarrantyDetails');
            if(!$checkDeletion['can']){
                throw new Exception('Cannot delete warranty type as it contains : '. $checkDeletion['relation']);
            }

            $this->productWarrantyRepository->delete($productWarranty);
            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            throw($exception);
        }
        return $productWarranty;
    }
}
