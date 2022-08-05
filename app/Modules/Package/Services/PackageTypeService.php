<?php


namespace App\Modules\Package\Services;

use App\Modules\Package\Repositories\PackageTypeRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class PackageTypeService
{
    protected $packageTypeRepository;

    public function __construct(PackageTypeRepository $packageTypeRepository)
    {
        $this->packageTypeRepository = $packageTypeRepository;
    }


    public function getAllPackageTypes()
    {
        return $this->packageTypeRepository->getAllPackageTypes();
    }

    public function findPackageTypeByCode($packageTypeCode)
    {
        return $this->packageTypeRepository->findPackageTypeByCode($packageTypeCode);
    }


    public function findOrFailPackageTypeByID($packageTypeID)
    {
        return $this->packageTypeRepository->findOrFailPackageTypeByID($packageTypeID);
    }

    public function findOrFailPackageTypeByCode($packageTypeCode)
    {
        return $this->packageTypeRepository->findOrFailPackageTypeByCode($packageTypeCode);
    }

    public function storePackageType($validated)
    {
        DB::beginTransaction();
        try {

            $packageType = $this->packageTypeRepository->create($validated);
            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            throw($exception);
        }
        return $packageType;
    }

    public function updatePackageType($validated, $packageTypeCode)
    {
        DB::beginTransaction();

        try {
            $packageType = $this->findOrFailPackageTypeByCode($packageTypeCode);
            $this->packageTypeRepository->update($validated, $packageType);
            DB::commit();
            return $packageType;

        } catch (Exception $exception) {
            DB::rollBack();
            throw($exception);
        }
    }

    public function deletePackageType($packageTypeCode)
    {
        DB::beginTransaction();
        try {
            $packageType = $this->findOrFailPackageTypeByCode($packageTypeCode);
            $checkDeletion = $packageType->canDelete('productPackageDetails');
            if(!$checkDeletion['can']){
                throw new Exception('Cannot delete package type as it contains : '. $checkDeletion['relation']);
            }

            $this->packageTypeRepository->delete($packageType);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw($exception);
        }
        return $packageType;
    }
}
