<?php


namespace App\Modules\Variants\Repositories;

use App\Modules\Variants\Models\VariantValue;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VariantValueRepository
{
    public function getAllVariantValuesOf($variant)
    {
        return $variant->variantValues()->latest()->get();
    }

    public function findVariantValueById($variantValueId)
    {
        return VariantValue::where('id', $variantValueId)->first();
    }

    public function findVariantValueByCode($variantValueCode)
    {
        return VariantValue::where('variant_value_code', $variantValueCode)->first();
    }

    public function findOrFailVariantValueById($variantValueId)
    {
        if (!$variantValue = $this->findVariantValueById($variantValueId)) {
            throw new ModelNotFoundException('No Such Variant Value Found');
        }
        return $variantValue;
    }

    public function findOrFailVariantValueByCode($variantValueCode)
    {
        if (!$variantValue = $this->findVariantValueByCode($variantValueCode)) {
            throw new ModelNotFoundException('No Such Variant Value Found');
        }
        return $variantValue;
    }

    public function storeVariantValueOf($variant, $validated)
    {
        $variantValue = new VariantValue;
        $authUserCode = getAuthUserCode();
        $validated['variant_value_code'] = $variantValue->generateVariantValueCode();
        $validated['slug'] =  str_replace(
            ' ',
            '-',
            (trim(strtolower($validated['variant_value_name'])))
        );
        $validated['created_by'] = $authUserCode;
        $validated['updated_by'] = $authUserCode;
        $variantValue = $variant->variantValues()->create($validated);
        return $variantValue->fresh();

    }

    public function updateVariantValue($validated, $variantValue)
    {
        $authUserCode = getAuthUserCode();
        $validated['slug'] =  str_replace(
            ' ',
            '-',
            (trim(strtolower($validated['variant_value_name'])))
        );
        $validated['updated_by'] = $authUserCode;
        $variantValue->update($validated);
        return $variantValue;

    }

    public function delete($variantValue)
    {
        $variantValue->delete();
        $variantValue->deleted_by= getAuthUserCode();
        $variantValue->save();
        return $variantValue;
    }

}
