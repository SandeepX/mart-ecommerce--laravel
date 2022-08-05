<?php

namespace App\Modules\Types\Repositories;

use App\Modules\Types\Models\CategoryType;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryTypeRepository
{
    private $categoryType;
    public function __construct(CategoryType $categoryType)
    {
      $this->categoryType = $categoryType;  
    }

    public function getAllCategoryTypes(){
        return $this->categoryType->latest()->get();
    }

    public function getCategoryTypesByCategory($category){
        return $category->categoryTypes;
    }


    public function getAllTrashedCategoryTypes($with = [])
    {
        return $this->categoryType->with($with)->withTrashed()->latest()->get();
    }

    public function findCategoryTypeById($categoryTypeId, $with = [])
    {
        return $this->categoryType->with($with)->where('id', $categoryTypeId)->first();
    }

    public function findCategoryTypeBySlug($categoryTypeSlug, $with = [])
    {
        return $this->categoryType->with($with)->where('slug', $categoryTypeSlug)->first();
    }


    public function findCategoryTypeByCode($categoryTypeCode, $with = [])
    {
        return $this->categoryType->with($with)->where('category_type_code', $categoryTypeCode)->first();
    }


    public function findOrFailCategoryTypeById($categoryTypeId, $with = [])
    {
        if(!$categoryType = $this->findCategoryTypeById($categoryTypeId,$with)){
            throw new ModelNotFoundException('No Such Category Type Found');
        }
        return $categoryType;
    }

    public function findOrFailCategoryTypeBySlug($categoryTypeSlug, $with = [])
    {
        if(!$categoryType = $this->findCategoryTypeBySlug($categoryTypeSlug,$with)){
            throw new ModelNotFoundException('No Such Category Type Found');
        }
        return $categoryType;
    }


    public function findOrFailCategoryTypeByCode($categoryTypeCode, $with = [])
    {
        if(!$categoryType = $this->findCategoryTypeByCode($categoryTypeCode,$with)){
            throw new ModelNotFoundException('No Such Category Type Found');
        }
        return $categoryType;
    }


    public function storeCategoryType($validated)
    {
        $authUserCode = getAuthUserCode();
        $validated['category_type_code'] = $this->categoryType->generatecategoryTypeCode();
        $validated['slug'] = make_slug($validated['category_type_name']);
        $validated['created_by'] = $authUserCode;
        $validated['updated_by'] = $authUserCode;
        $categoryType = $this->categoryType->create($validated);
        return $categoryType->fresh();

    }

    public function updateCategoryType($validated, $categoryType)
    {
        $validated['slug'] = make_slug($validated['category_type_name']);
        $validated['updated_by'] = getAuthUserCode();
        $categoryType->update($validated);
        return $categoryType;

    }

    public function delete($categoryType)
    {
        $categoryType->delete();
        $categoryType->deleted_by= getAuthUserCode();
        $categoryType->save();
        return $categoryType;
    }





}