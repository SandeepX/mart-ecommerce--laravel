<?php

namespace App\Modules\Types\Repositories;
use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\Types\Models\StoreType;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class StoreTypeRepository
{
    use ImageService;
    private $storeType;

    public function __construct(StoreType $storeType)
    {
        $this->storeType = $storeType;
    }

    public function getAllStoreTypes($activeStatus = false)
    {
//        $this->storeType = $this->storeType->query();
//        return StoreType::where('deleted_at', '')->get();
//
//        if ($activeStatus) {
//            $this->storeType = $this->storeType->active();
//        }
        return $this->storeType->orderBy('sort_order','ASC')->get();
    }


    public function getAllTrashedStoreTypes($with = [])
    {
        return $this->storeType->with($with)->withTrashed()->latest()->get();
    }

    public function findStoreTypeById($storeTypeId, $with = [])
    {
        return $this->storeType->with($with)->where('id', $storeTypeId)->first();
    }

    public function findStoreTypeBySlug($storeTypeSlug, $with = [])
    {
        return $this->storeType->with($with)->where('store_type_slug', $storeTypeSlug)->first();
    }


    public function findStoreTypeByCode($storeTypeCode, $with = [])
    {
        return $this->storeType->with($with)->where('store_type_code', $storeTypeCode)->first();
    }


    public function findOrFailStoreTypeById($storeTypeId, $with = [])
    {
        if (!$storeType = $this->findStoreTypeById($storeTypeId, $with)) {
            throw new ModelNotFoundException('No Such Store Type Found');
        }
        return $storeType;
    }

    public function findOrFailStoreTypeBySlug($storeTypeSlug, $with = [])
    {
        if (!$storeType= $this->findStoreTypeBySlug($storeTypeSlug, $with)) {
            throw new ModelNotFoundException('No Such Store Type Found');
        }
        return $storeType;
    }


    public function findOrFailStoreTypeByCode($storeTypeCode, $with = [])
    {
        if (!$storeType= $this->findStoreTypeByCode($storeTypeCode, $with)) {
            throw new ModelNotFoundException('No Such Store Type Found');
        }
        return $storeType;
    }


    public function storeStoreType($validated)
    {
        try {
        $authUserCode = getAuthUserCode();
        $validated['image'] = $this->storeImageInServer($validated['image'], StoreType::IMAGE_PATH);
        $validated['store_type_code'] = $this->storeType->generateStoreTypeCode();
        $validated['store_type_slug'] = make_slug($validated['store_type_name']);
        $validated['created_by'] = $authUserCode;
//        $validated['updated_by'] = $authUserCode;
        $storeType = $this->storeType->create($validated);
        return $storeType->fresh();
        }catch (Exception $e) {
        $this->deleteImageFromServer(StoreType::IMAGE_PATH, $validated['image']);
        throw $e;
    }
    }

    public function updateStoreType($validated, $storeType)
    {
        try {
            if(isset($validated['image'])){
                $this->deleteImageFromServer(StoreType::IMAGE_PATH, $validated['image']);
                $validated['image'] = $this->storeImageInServer($validated['image'], StoreType::IMAGE_PATH);
            }

            $validated['store_type_slug'] = make_slug($validated['store_type_name']);
//        $validated['updated_by'] = getAuthUserCode();
            $storeType->update($validated);
            return $storeType;
        }catch (Exception $e) {
            $this->deleteImageFromServer(StoreType::IMAGE_PATH, $validated['image']);
            throw $e;
        }


    }

    public function delete($storeType)
    {
        $storeType->delete();
        $storeType->deleted_by = getAuthUserCode();
        if(isset($storeType->image)){
            $this->deleteImageFromServer(StoreType::IMAGE_PATH, $storeType->image);
        }
        $storeType->save();
        return $storeType;
    }

    public function changeStoreTypeStatus($storeType,$status){
        try{

            $storeType->is_active = $status;
            $storeType->save();

            return $storeType;
        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function getAllActiveStoreTypes()
    {

        return $this->storeType->where('is_active',1)->orderBy('sort_order','ASC')->get();
    }
}
