<?php


namespace App\Modules\Variants\Services;

use App\Modules\Variants\Repositories\VariantRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class VariantService
{
    protected $variantRepository;

    public function __construct(VariantRepository $variantRepository)
    {
        $this->variantRepository = $variantRepository;
    }


    public function getAllVariants($with = [])
    {
        try {
            return $this->variantRepository->getAllVariants($with);
        } catch (Exception $exception) {
            throw  $exception;
        }
    }

    public function getAllVariantsWithTrashed($with = [])
    {
        try {
            return $this->variantRepository->getAllVariantsWithTrashed($with);
        } catch (Exception $exception) {
            throw  $exception;
        }
    }

    public function findVariantById($variantId, $with = [])
    {
        return $this->variantRepository->findVariantById($variantId, $with);
    }

    public function findVariantByCode($variantCode, $with = [])
    {
        return $this->variantRepository->findVariantByCode($variantCode, $with);
    }

    public function findOrFailVariantById($variantId, $with = [])
    {
        return $this->variantRepository->findOrFailVariantById($variantId, $with);
    }

    public function findOrFailVariantByCode($variantCode, $with = [])
    {
        return $this->variantRepository->findOrFailVariantByCode($variantCode, $with);
    }


    public function storeVariant($validated)
    {
        DB::beginTransaction();
        try {
            $variant = $this->variantRepository->storeVariant($validated);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return $variant;
    }

    public function updateVariant($validated, $variantCode)
    {
        DB::beginTransaction();
        try {
            $variant = $this->findOrFailVariantByCode($variantCode);
            $this->variantRepository->updateVariant($validated, $variant);
            DB::commit();
            return $variant;

        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
    }

    public function deleteVariant($variantCode)
    {
        DB::beginTransaction();
        try {
            $variant = $this->findOrFailVariantByCode($variantCode);
            $checkDeletion = $variant->canDelete('variantValues');
            if(!$checkDeletion['can']){
                throw new Exception('Cannot delete variant as it contains : '. $checkDeletion['relation']);
            }
            $variant = $this->variantRepository->delete($variant);
            DB::commit();
            return $variant;

        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
    }

}
