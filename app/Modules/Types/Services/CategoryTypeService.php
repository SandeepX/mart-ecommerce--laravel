<?php

namespace App\Modules\Types\Services;

use App\Modules\Types\Repositories\CategoryTypeRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class CategoryTypeService
{
    protected $categoryTypeRepository;

    public function __construct(CategoryTypeRepository $categoryTypeRepository)
    {
        $this->categoryTypeRepository = $categoryTypeRepository;
    }


    public function getAllCategoryTypes(){
        return $this->categoryTypeRepository->getAllCategoryTypes();
    }

    public function getCategoryTypesByCategory($category){
        return $this->categoryTypeRepository->getCategoryTypesByCategory($category);
    }

    public function findCategoryTypeById($categoryTypeId, $with = [])
    {
        return $this->categoryTypeRepository->findCategoryTypeById($categoryTypeId, $with);
    }

    public function findCategoryTypeByCode($categoryTypeCode, $with = [])
    {
        return $this->categoryTypeRepository->findCategoryTypeByCode($categoryTypeCode, $with);
    }

    public function findOrFailCategoryTypeById($categoryTypeId, $with = [])
    {
        return $this->categoryTypeRepository->findCategoryTypeById($categoryTypeId, $with);
    }

    public function findOrFailCategoryTypeByCode($categoryTypeCode, $with = [])
    {
        return $this->categoryTypeRepository->findOrFailCategoryTypeByCode($categoryTypeCode, $with);
    }

    public function storeCategoryType($validated)
    {
        DB::beginTransaction();
        try {
            $categoryType = $this->categoryTypeRepository->storeCategoryType($validated);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return $categoryType;
    }

    public function updateCategoryType($validated, $categoryTypeCode)
    {
        DB::beginTransaction();
        try {
            $categoryType = $this->categoryTypeRepository->findOrFailCategoryTypeByCode($categoryTypeCode);
            $this->categoryTypeRepository->updateCategoryType($validated, $categoryType);
            DB::commit();
            return $categoryType;

        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
    }

    public function deleteCategoryType($categoryTypeCode)
    {
        DB::beginTransaction();
        try {
            $categoryType = $this->categoryTypeRepository->findOrFailCategoryTypeByCode($categoryTypeCode);
            $checkDeletion = $categoryType->canDelete('categories');
            if(!$checkDeletion['can']){
                throw new Exception('Cannot delete category type as it contains : '. $checkDeletion['relation']);
            }
            $categoryType = $this->categoryTypeRepository->delete($categoryType);
            DB::commit();
            return $categoryType;

        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
    }






}