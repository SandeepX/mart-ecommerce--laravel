<?php

namespace App\Modules\Types\Repositories;

use App\Modules\Types\Models\StoreSize;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StoreSizeRepository
{
    private $storeSize;
    public function __construct(StoreSize $storeSize)
    {
      $this->storeSize = $storeSize;  
    }

    public function getAllStoreSizes($activeStatus=false){
        $this->storeSize= $this->storeSize->query();

        if ($activeStatus){
            $this->storeSize= $this->storeSize->active();
        }

        return $this->storeSize->latest()->get();
    }


    public function getAllTrashedStoreSizes($with = [])
    {
        return $this->storeSize->with($with)->withTrashed()->latest()->get();
    }

    public function findStoreSizeById($storeSizeId, $with = [])
    {
        return $this->storeSize->with($with)->where('id', $storeSizeId)->first();
    }

    public function findStoreSizeBySlug($storeSizeSlug, $with = [])
    {
        return $this->storeSize->with($with)->where('slug', $storeSizeSlug)->first();
    }


    public function findStoreSizeByCode($storeSizeCode, $with = [])
    {
        return $this->storeSize->with($with)->where('store_size_code', $storeSizeCode)->first();
    }


    public function findOrFailStoreSizeById($storeSizeId, $with = [])
    {
        if(!$storeSize = $this->findStoreSizeById($storeSizeId,$with)){
            throw new ModelNotFoundException('No Such Store Size Found');
        }
        return $storeSize;
    }

    public function findOrFailStoreSizeBySlug($storeSizeSlug, $with = [])
    {
        if(!$storeSize = $this->findStoreSizeBySlug($storeSizeSlug,$with)){
            throw new ModelNotFoundException('No Such Store Size Found');
        }
        return $storeSize;
    }


    public function findOrFailStoreSizeByCode($storeSizeCode, $with = [])
    {
        if(!$storeSize = $this->findStoreSizeByCode($storeSizeCode,$with)){
            throw new ModelNotFoundException('No Such Store Size Found');
        }
        return $storeSize;
    }


    public function storeStoreSize($validated)
    {
        $authUserCode = getAuthUserCode();
        $validated['store_size_code'] = $this->storeSize->generateStoreSizeCode();
        $validated['slug'] = make_slug($validated['store_size_name']);
        $validated['created_by'] = $authUserCode;
        $validated['updated_by'] = $authUserCode;
        $storeSize = $this->storeSize->create($validated);
        return $storeSize->fresh();

    }

    public function updateStoreSize($validated, $storeSize)
    {
        $validated['slug'] = make_slug($validated['store_size_name']);
        $validated['updated_by'] = getAuthUserCode();
        $storeSize->update($validated);
        return $storeSize;

    }

    public function delete($storeSize)
    {
        $storeSize->delete();
        $storeSize->deleted_by= getAuthUserCode();
        $storeSize->save();
        return $storeSize;
    }





}