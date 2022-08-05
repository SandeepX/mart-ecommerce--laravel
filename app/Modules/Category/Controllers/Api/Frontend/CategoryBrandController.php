<?php

namespace App\Modules\Category\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Modules\Brand\Resources\BrandResource;
use App\Modules\Category\Services\CategoryService;
use Exception;

class CategoryBrandController extends Controller{

    private $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function getBrandsOfRootCategory($rootCategoryCode){
        try{
            $brands = $this->categoryService->getBrandsByCategoryCode($rootCategoryCode);
            $brands = BrandResource::collection($brands);
            return sendSuccessResponse('Data Found', $brands);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }
}