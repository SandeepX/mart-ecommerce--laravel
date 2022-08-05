<?php


namespace App\Modules\Package\Repositories;

use App\Modules\Package\Models\PackageType;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PackageTypeRepository
{
    public function getAllPackageTypes()
    {
        return PackageType::latest()->get();
    }

    public function findPackageTypeByID($packageTypeID)
    {
        return PackageType::where('id', $packageTypeID)->first();
    }

    public function findPackageTypeByCode($packageTypeCode)
    {
        return PackageType::where('package_code', $packageTypeCode)->first();
    }


    public function findOrFailPackageTypeByID($packageTypeID)
    {
        if($packageType = $this->findPackageTypeByID($packageTypeID)){
            return $packageType;
        }
        throw new ModelNotFoundException('No Such Package Type Found !');

    }

    public function findOrFailPackageTypeByCode($packageTypeCode)
    {
        if($packageType = $this->findPackageTypeByCode($packageTypeCode)){
            return $packageType;
        }
        throw new ModelNotFoundException('No Such Package Type Found !');

    }

    public function create($validated)
    {
        $packageType = new PackageType;
        $authUserCode = getAuthUserCode();
        $validated['package_code'] = $packageType->generatePackageCode();
        $validated['slug'] = make_slug($validated['package_name']);
        $validated['created_by'] = $authUserCode;
        $validated['updated_by'] = $authUserCode;
        return PackageType::create($validated)->fresh();

    }

    public function update($validated, $packageType)
    {
        $validated['updated_by'] = getAuthUserCode();
        $packageType->update($validated);
        return $packageType;

    }

    public function delete($packageType)
    {
        $packageType->delete();
        $packageType->deleted_by = getAuthUserCode();
        $packageType->save();
        return $packageType;
    }
}
