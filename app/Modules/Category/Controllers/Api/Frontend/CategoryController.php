<?php

namespace App\Modules\Category\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Modules\Category\Resources\CategoryPathResource;
use App\Modules\Category\Services\CategoryService;
use App\Modules\Category\Resources\CategoryResource;
use App\Modules\Category\Resources\CategoryTreeResource;
use App\Modules\Category\Resources\CategoryTreeReverseResource;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function getRootCategories(){
        try{
            $rootCategories = $this->categoryService->getRootCategories();
            $rootCategories = CategoryResource::collection($rootCategories);
            return sendSuccessResponse('Data Found',  $rootCategories);

        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function getCategoryTree(){
        try{
            $rootCategories = $this->categoryService->getRootCategories();
            $categoryTree = CategoryTreeResource::collection($rootCategories);
            return sendSuccessResponse('Data Found',  $categoryTree);

        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function getCategoryFamily($categoryCode){
        try{
            $categories = $this->categoryService->getCategoryFamily($categoryCode);
            $categories = CategoryResource::collection($categories);
            return sendSuccessResponse('Data Found',  $categories);

        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function getCategoryReverseTree($categoryCode){
        try{
            $category = $this->categoryService->getCategoryByCode($categoryCode);
            $categoryReverseTree = new CategoryTreeReverseResource($category);
            return sendSuccessResponse('Data Found',  $categoryReverseTree);

        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function getLowerCategories($categoryCode){
        try{
            $lowerCategories = $this->categoryService->getLowerCategoriesByCode($categoryCode);
            $lowerCategories = CategoryResource::collection($lowerCategories);
            return sendSuccessResponse('Data Found',  $lowerCategories);

        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function getLowerCategoriesByCatSlug($categorySlug){
        try{
            $lowerCategories = $this->categoryService->getLowerCategoriesByCatSlug($categorySlug);
            $lowerCategories = CategoryResource::collection($lowerCategories);
            return sendSuccessResponse('Data Found',  $lowerCategories);

        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function getDaddyWithHisSiblingCategories($childCategoryCode){
        try{
            $category = $this->categoryService->getCategoryByCode($childCategoryCode);
            $daddyWithHisSiblings = $this->categoryService
                                        ->getDaddyWithHisSiblingCategories(
                                            $category->category_code
                                        );
            $daddyWithHisSiblings = CategoryTreeResource::collection($daddyWithHisSiblings);
            return sendSuccessResponse('Data Found',  $daddyWithHisSiblings);

        }catch(Exception $exception){
            return sendErrorResponse('Cannot find the categories', 404);
        }
    }

    public function getDaddyWithHisSiblingCategoriesBySlug($childCategorySlug){
        try{
            $category = $this->categoryService->getCategoryBySlug($childCategorySlug);
            $daddyWithHisSiblings = $this->categoryService
                                        ->getDaddyWithHisSiblingCategories(
                                            $category->category_code
                                        );
            $daddyWithHisSiblings = CategoryTreeResource::collection($daddyWithHisSiblings);
            return sendSuccessResponse('Data Found',  $daddyWithHisSiblings);

        }catch(Exception $exception){
            return sendErrorResponse('Cannot find the categories', 404);
        }
    }


    public function getCategoryInfo($categorySlug){
        try{
            $category = $this->categoryService->getCategoryBySlug($categorySlug);
            return sendSuccessResponse('Data Found',  new CategoryResource($category));

        }catch(Exception $exception){
            return sendErrorResponse('Cannot find the category info', 404);
        }
    }

    public function searchCategoryPath(Request $request){
        try{
            $searchTerm = $request->get('category_name');
            $categoryPaths = $this->categoryService->searchCategoryPath($searchTerm);
            $categoryPaths = CategoryPathResource::collection($categoryPaths);

            return sendSuccessResponse('Data Found',  $categoryPaths);

        }catch(Exception $exception){
            return sendErrorResponse('Cannot find the category info', 404);
        }
    }


}
