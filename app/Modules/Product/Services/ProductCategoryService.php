<?php

namespace App\Modules\Product\Services;

use App\Modules\Category\Repositories\CategoryRepository;
use App\Modules\Product\Helpers\ProductCategoryHelper;
use App\Modules\Product\Repositories\ProductCategoryRepository;

class ProductCategoryService
{
    private $productCategoryRepository;
    private $categoryRepository;

    public function __construct(ProductCategoryRepository $productCategoryRepository, CategoryRepository $categoryRepository)
    {
        $this->productCategoryRepository = $productCategoryRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function syncProductCategories($product, $leafCategoryCode){
        $categoryFamily = $this->categoryRepository->getCategoryFamily($leafCategoryCode);
        $categoryCodes = array_column($categoryFamily, 'category_code');
        $this->productCategoryRepository->syncProductCategories($product, $categoryCodes);
    }


    public function getProductsOfCategories(array $categories){
        return ProductCategoryHelper::getProductsOfCategoriesWithPagination($categories);
    }

}