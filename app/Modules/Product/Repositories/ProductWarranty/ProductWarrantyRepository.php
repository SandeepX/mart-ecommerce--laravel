<?php


namespace App\Modules\Product\Repositories\ProductWarranty;

use App\Modules\Product\Models\ProductWarranty;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductWarrantyRepository
{
    public function getAllProductWarranties()
    {
        return ProductWarranty::all();
    }

    public function findProductWarrantyByID($productWarrantyID)
    {
        return ProductWarranty::where('id',$productWarrantyID)->first();
    }


    public function findProductWarrantyByCode($productWarrantyCode)
    {
        return ProductWarranty::where('warranty_code',$productWarrantyCode)->first();
    }


    public function findOrFailProductWarrantyByID($productWarrantyID)
    {
        if($productWarranty = $this->findProductWarrantyByID($productWarrantyID)){
            return $productWarranty;
        }
        throw new ModelNotFoundException('No Such Warranty Found !');
    }

    public function findOrFailProductWarrantyByCode($productWarrantyCode)
    {
        if($productWarranty = $this->findProductWarrantyByCode($productWarrantyCode)){
            return $productWarranty;
        }
        throw new ModelNotFoundException('No Such Warranty Found !');
    }

    public function create($validated)
    {
        $productWarranty = new ProductWarranty;
        $authUserCode = getAuthUserCode();
        $validated['warranty_code'] = $productWarranty->generateWarrantyCode();
        $validated['slug'] = make_slug($validated['warranty_name']);
        $validated['created_by'] = $authUserCode;
        $validated['updated_by'] = $authUserCode;
        return ProductWarranty::create($validated)->fresh();

    }

    public function update($validated, $productWarranty)
    {
        $validated['updated_by'] = getAuthUserCode();
        $productWarranty->update($validated);
        return $productWarranty;

    }

    public function delete($productWarranty)
    {
        $productWarranty->delete();
        $productWarranty->deleted_by = getAuthUserCode();
        $productWarranty->save();
        return $productWarranty;
    }
}
