<?php

namespace App\Modules\Category\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Category\Services\CategoryService;
use App\Modules\Category\Requests\CategoryCreateRequest;
use App\Modules\Category\Requests\CategoryUpdateRequest;
use App\Modules\Category\Resources\CategoryResource;
use App\Modules\Category\Models\CategoryMaster;
use Exception;

class CategoryController extends Controller
{

    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{

            $categories = $this->categoryService->getAllCategories();
            return CategoryResource::collection($categories);

        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), true, $exception->getCode());
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryCreateRequest $request)
    {
        try{
            $validated = $request->validated();
            $category = $this->categoryService->create($validated);
            $category = new CategoryResource($category);
            return sendSuccessResponse('Category Created Successfully', $category);

        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 500);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($categoryCode)
    {
        try{

            $category = $this->categoryService->getCategoryByCode($categoryCode);
            return new CategoryResource($category);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(),404);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryUpdateRequest $request, $categoryCode)
    {
        try{
            $validated = $request->validated();
            $category = $this->categoryService->update($validated, $categoryCode);
            $category = new CategoryResource($category);
            return sendSuccessResponse('Category Updated Successfully', $category);

        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(),  $exception->getCode());
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($categoryCode)
    {
        try{

            $category = $this->categoryService->delete($categoryCode);
            $category = new CategoryResource($category);

            return sendSuccessResponse('Category Deleted Successfully', $category);

        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(),  $exception->getCode());
        }
    }

}
