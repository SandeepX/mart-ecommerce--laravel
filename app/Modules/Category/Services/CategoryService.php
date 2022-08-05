<?php


namespace App\Modules\Category\Services;

use App\Modules\Category\Repositories\CategoryRepository;
use App\Modules\Category\Repositories\CategoryTypeRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class CategoryService
{
    protected $categoryRepository;
    protected $categoryTypeRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }


    public function getCategoryMaster($select = '*'){
        return $this->categoryRepository->getCategoryMaster($select);
    }

    public function getAllCategories()
    {
        return $this->categoryRepository->getAllCategories();
    }

    public function searchCategoryPath($searchTerm){
       return $this->categoryRepository->searchCategoryPath($searchTerm);
    }

    public function getRootCategories($with = [])
    {
        return $this->categoryRepository->getRootCategories($with);
    }

    public function getCategoryFamily($categoryCode)
    {
        $categoryFamily =  $this->categoryRepository->getCategoryFamily($categoryCode);
        $categoryFamilyCodes = array_column($categoryFamily, 'category_code');
        $categories = $this->categoryRepository->getCategoriesByCodes($categoryFamilyCodes);
        return $categories;
    }

    public function getRootCategoriesHavingBrands($with = [])
    {
        return $this->categoryRepository->getRootCategoriesHavingBrands($with);
    }


    public function getRootCategoriesNotHavingBrands()
    {
        return $this->categoryRepository->getRootCategoriesNotHavingBrands();
    }

    public function getLowerCategoriesByCode($categoryCode)
    {
        $category = $this->categoryRepository->getCategoryByCode($categoryCode, ['lowerCategories']);
        return $this->categoryRepository->getLowerCategories($category);
    }

    public function getLowerCategoriesByCatSlug($categoryCatSlug)
    {
        $category = $this->categoryRepository->getCategoryBySlug($categoryCatSlug, ['lowerCategories']);
        return $this->categoryRepository->getLowerCategories($category);
    }

    public function getDaddyWithHisSiblingCategories($childCategoryCode){
       return $this->categoryRepository->getDaddyWithHisSiblingCategories($childCategoryCode);
    }

    public function getCategoryByCode($categoryCode, $with = [])
    {
        try {
            return $this->categoryRepository->getCategoryByCode($categoryCode, $with);
        } catch (Exception $exception) {
            throw ($exception);
        }
    }

    public function getCategoryBySlug($categorySlug)
    {
        try {

            return $this->categoryRepository->getCategoryBySlug($categorySlug);
        } catch (Exception $exception) {
            throw ($exception);
        }
    }


    public function getCategoriesBySlugs(array $categorySlugs, $with = [])
    {
        return $this->categoryRepository->getCategoriesBySlugs($categorySlugs);
    }

    public function getBrandsByCategoryCode($categoryCode)
    {
        $category = $this->categoryRepository->getCategoryByCode($categoryCode);
        return $this->categoryRepository->getBrandsByCategory($category);
    }

    public function create($validated)
    {
        DB::beginTransaction();
        try {

            $category = $this->categoryRepository->create($validated);

            DB::commit();
            return $category;
        } catch (Exception $exception) {
            DB::rollBack();
            throw ($exception);
        }
    }

    public function update($validated, $categoryCode)
    {
        DB::beginTransaction();
        try {


            $category = $this->categoryRepository->update($validated, $categoryCode);
            DB::commit();
            return $category;
        } catch (Exception $exception) {
            DB::rollBack();
            throw ($exception);
        }
    }

    public function delete($categoryCode)
    {
        DB::beginTransaction();
        try {
            $category = $this->categoryRepository->delete($categoryCode);
            DB::commit();
            return $category;
        } catch (Exception $exception) {
            DB::rollBack();
            throw ($exception);
        }
    }

    public function syncCategoryBrands($validated)
    {
        DB::beginTransaction();
        try {
            $category = $this->categoryRepository->getCategoryByCode($validated['category_code']);
            $category = $this->categoryRepository->syncCategoryBrands($category, $validated['brand_codes']);
            DB::commit();
            return $category;
        } catch (Exception $exception) {
            DB::rollBack();
            throw ($exception);
        }
    }
}
