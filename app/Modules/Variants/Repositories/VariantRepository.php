<?php


namespace App\Modules\Variants\Repositories;

use App\Modules\Variants\Models\Variant;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VariantRepository
{
    public function getAllVariants($with = [])
    {
        return Variant::with($with)->withCount('variantValues')->latest()->get();
    }

    public function getAllVariantsWithTrashed($with = [])
    {
        return Variant::with($with)->withTrashed()->withCount('variantValues')->latest()->get();
    }

    public function findVariantById($variantId, $with = [])
    {
        return Variant::with($with)->where('id', ($variantId))->first();
    }

    public function findVariantBySlug($variantSlug, $with = [])
    {
        return Variant::with($with)->where('slug', $variantSlug)->first();
    }


    public function findVariantByCode($variantCode, $with = [])
    {
        return Variant::with($with)->where('variant_code', $variantCode)->first();
    }


    public function findOrFailVariantById($variantId, $with = [])
    {
        if(!$variant = $this->findVariantById($variantId,$with)){
            throw new ModelNotFoundException('No Such Variant Found');
        }
        return $variant;
    }

    public function findOrFailVariantBySlug($variantSlug, $with = [])
    {
        if(!$variant = $this->findVariantBySlug($variantSlug,$with)){
            throw new ModelNotFoundException('No Such Variant Found');
        }
        return $variant;
    }


    public function findOrFailVariantByCode($variantCode, $with = [])
    {
        if(!$variant = $this->findVariantByCode($variantCode,$with)){
            throw new ModelNotFoundException('No Such Variant Found');
        }
        return $variant;
    }


    public function storeVariant($validated)
    {
        $authUserCode = getAuthUserCode();
        $variant = new Variant;
        $validated['variant_code'] = $variant->generateVariantCode();
        $validated['slug'] = make_slug($validated['variant_name']);
        $validated['created_by'] = $authUserCode;
        $validated['updated_by'] = $authUserCode;
        $variant = Variant::create($validated);
        return $variant->fresh();

    }

    public function updateVariant($validated, $variant)
    {
        $validated['slug'] = make_slug($validated['variant_name']);
        $validated['updated_by'] = getAuthUserCode();
        $variant->update($validated);
        return $variant;

    }

    public function delete($variant)
    {
        $variant->delete();
        $variant->deleted_by= getAuthUserCode();
        $variant->save();
        return $variant;
    }

}
