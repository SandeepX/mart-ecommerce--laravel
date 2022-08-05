<?php


namespace App\Modules\Variants\Services;


use App\Modules\Variants\Repositories\VariantRepository;
use App\Modules\Variants\Repositories\VariantValueRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class VariantValueService
{
    private $variantValueRepository;
    private $variantRepository;

    public function __construct(VariantRepository $variantRepository, VariantValueRepository $variantValueRepository)
    {
        $this->variantRepository = $variantRepository;
        $this->variantValueRepository = $variantValueRepository;
    }

    public function getAllVariantValuesOf($variant)
    {
        return $this->variantValueRepository->getAllVariantValuesOf($variant);
    }

    public function findVariantValueById($variantValueId)
    {
        return $this->variantValueRepository->findVariantValueById($variantValueId);
    }

    public function findVariantValueByCode($variantValueCode)
    {
        return $this->variantValueRepository->findVariantValueByCode($variantValueCode);
    }

    public function findOrFailVariantValueById($variantValueId)
    {
        return $this->variantValueRepository->findOrFailVariantValueById($variantValueId);
    }

    public function findOrFailVariantValueByCode($variantValueCode)
    {
        return $this->variantValueRepository->findOrFailVariantValueByCode($variantValueCode);
    }


    public function storeVariantValue($validated, $variantID)
    {
        DB::beginTransaction();
        try {
            $variant = $this->variantRepository->findVariantById($variantID);
            $variantValue = $this->variantValueRepository->storeVariantValueOf($variant, $validated);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return $variantValue;

    }

    public function updateVariantValue($validated, $variantValueCode)
    {
        DB::beginTransaction();

        try {
            $variantValue = $this->variantValueRepository->findOrFailVariantValueByCode($variantValueCode);

            $checkDeletion = $variantValue->canDelete('productVariantDetails');
            if(!$checkDeletion['can']){
                throw new Exception('Cannot Edit variant value as it is contained in : '. $checkDeletion['relation']);
            }

            $this->variantValueRepository->updateVariantValue($validated, $variantValue);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
        return $variantValue;
    }

    public function deleteVariantValue($variantValueCode)
    {
        DB::beginTransaction();
        try {
            $variantValue = $this->variantValueRepository->findOrFailVariantValueByCode($variantValueCode);
            $checkDeletion = $variantValue->canDelete('productVariantDetails');
            if(!$checkDeletion['can']){
                throw new Exception('Cannot delete variant value as it is contained in : '. $checkDeletion['relation']);
            }
            $this->variantValueRepository->delete($variantValue);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
           throw  $exception;
        }
        return $variantValue;
    }

}
