<?php


namespace App\Modules\Product\Repositories\ProductSensitivity;

use App\Modules\Product\Models\ProductSensitivity;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductSensitivityRepository
{
    public function getAllProductSensitivities()
    {
        return ProductSensitivity::latest()->get();
    }

    public function findProductSensitivityByID($productSensitivityID)
    {
        return ProductSensitivity::where('id', $productSensitivityID)->first();
    }

    public function findProductSensitivityByCode($productSensitivityCode)
    {
        return ProductSensitivity::where('sensitivity_code', $productSensitivityCode)->first();
    }


    public function findOrFailProductSensitivityByID($productSensitivityID)
    {
        if($productSensitivity = $this->findProductSensitivityByID($productSensitivityID)){
            return $productSensitivity;
        }
        throw new ModelNotFoundException('No Such Sensitivity Found !');
    }

    public function findOrFailProductSensitivityByCode($productSensitivityCode)
    {
        if($productSensitivity = $this->findProductSensitivityByCode($productSensitivityCode)){
            return $productSensitivity;
        }
        throw new ModelNotFoundException('No Such Sensitivity Found !');
    }

    public function create($validated)
    {
        $productSensitivity = new ProductSensitivity;
        $authUserCode = getAuthUserCode();
        $validated['sensitivity_code'] =$productSensitivity->generateSensitivityCode();
        $validated['slug'] = make_slug($validated['sensitivity_name']);
        $validated['created_by'] = $authUserCode;
        $validated['updated_by'] = $authUserCode;
        return ProductSensitivity::create($validated)->fresh();

    }

    public function update($validated, $productSensitivity)
    {
        $validated['updated_by'] = getAuthUserCode();
        $productSensitivity->update($validated);
        return $productSensitivity;
    }

    public function delete($productSensitivity)
    {
        $productSensitivity->delete();
        $productSensitivity->deleted_by = getAuthUserCode();
        $productSensitivity->save();
        return $productSensitivity;
    }
}
