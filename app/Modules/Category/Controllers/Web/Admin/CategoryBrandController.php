<?php

namespace App\Modules\Category\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Brand\Services\BrandService;
use App\Modules\Category\Requests\CategoryBrandRequest;
use App\Modules\Category\Services\CategoryService;

class CategoryBrandController extends BaseController{

    public $title = 'Category';
    public $base_route = 'admin.categories';
    public $sub_icon = 'file';
    public $module = 'Category::';


    private $view;
    private $categoryService;
    private $brandService;

    public function __construct(CategoryService $categoryService, BrandService $brandService)
    {
        $this->middleware('permission:View Category Brand List', ['only' => ['getCategoryBrands']]);
        $this->middleware('permission:Create Category Brand', ['only' => ['brandCategoryPage','syncCategoryBrands']]);
        $this->middleware('permission:Show Category Brand', ['only' => ['showCategoryBrands']]);
        $this->middleware('permission:Update Category Brand', ['only' => ['editCategoryBrands','syncCategoryBrands']]);

        $this->view = 'admin.';
        $this->categoryService = $categoryService;
        $this->brandService = $brandService;

    }

    public function getCategoryBrands()
    {   
        try{
            $categories = $this->categoryService->getRootCategoriesHavingBrands(['brands']);
            return view(Parent::loadViewData($this->module.$this->view.'category-brands.index'),compact('categories'));
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
        
    }

    public function showCategoryBrands($categoryCode)
    {   
        try{
            $category = $this->categoryService->getCategoryByCode($categoryCode);
            $brands = $this->categoryService->getBrandsByCategoryCode($categoryCode);
            return view(Parent::loadViewData($this->module.$this->view.'category-brands.show'),compact('category', 'brands'));
           
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function brandCategoryPage()
    {
        try{
            $categories = $this->categoryService->getRootCategoriesNotHavingBrands();
            $brands = $this->brandService->getAllBrands();
            return view(Parent::loadViewData($this->module.$this->view.'category-brands.create'),compact('categories', 'brands'));
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function syncCategoryBrands(CategoryBrandRequest $request){
        try{
            $validated = $request->validated();
            $category = $this->categoryService->syncCategoryBrands($validated);
            return redirect()->back()->with('success', 'Brands Synced To '.$category->category_name.' Successfully');
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function editCategoryBrands($categoryCode){
        try{
            $category =  $this->categoryService->getCategoryByCode($categoryCode,['brands']);

            $brandCodes = $category->brands->pluck('brand_code')->toArray();
        
            $brands = $this->brandService->getAllBrands();
            return view(Parent::loadViewData($this->module.$this->view.'category-brands.edit'),compact('category', 'brands', 'brandCodes'));
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        } 
    }

}