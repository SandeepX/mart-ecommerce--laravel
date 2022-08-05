<?php


namespace App\Modules\Brand\Services;

use App\Modules\Brand\Repositories\BrandRepository;
use DB;
use Exception;


class BrandService
{

    private $brandRepository;

    public function __construct(BrandRepository $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }

    public function getAllBrands()
    {
        $brands = $this->brandRepository->getAllBrands();
        return $brands;
    }

    public function findBrandById($brandId)
    {
        return $this->brandRepository->findBrandById($brandId);
    }


    public function findBrandByCode($brandCode)
    {
        return $this->brandRepository->findBrandByCode($brandCode);
    }

    public function findBrandBySlug($brandSlug)
    {
        return $this->brandRepository->findBrandBySlug($brandSlug);
    }

    public function findOrFailBrandById($brandId)
    {
        return $this->brandRepository->findOrFailBrandById($brandId);
    }


    public function findOrFailBrandByCode($brandCode)
    {
        return $this->brandRepository->findOrFailBrandByCode($brandCode);
    }

    public function findOrFailBrandBySlug($brandSlug)
    {
        return $this->brandRepository->findOrFailBrandBySlug($brandSlug);
    }
    public function getFeaturedBrands($limit){
        return $this->brandRepository->getFeaturedBrands($limit);
    }
    public function brandDetails($brandSlug){
        return $this->brandRepository->brandDetails($brandSlug);
    }

    public function storeBrand($validated)
    {

        DB::beginTransaction();
        try {
            $brand = $this->brandRepository->create($validated);
            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
        return $brand;


    }

    public function updateBrand($validated, $brandCode)
    {
        if(!isset($validated['is_featured']))
            $validated['is_featured']=0;
        DB::beginTransaction();
        try {
            $brand = $this->findBrandByCode($brandCode);
            $this->brandRepository->update($validated, $brand);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
        return $brand;
    }

    public function deleteBrand($brandCode)
    {
        DB::beginTransaction();
        try {
            $brand = $this->findBrandByCode($brandCode);
            $brandDeletion = $brand->canDelete('products','categories');

            if(!$brandDeletion['can']){
               throw new Exception('Cannot delete brand as it contains : '. $brandDeletion['relation']);
            }
            $this->brandRepository->delete($brand);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return $brand;
    }
}
