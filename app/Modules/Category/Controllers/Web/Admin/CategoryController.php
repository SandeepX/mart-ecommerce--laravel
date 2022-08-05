<?php

namespace App\Modules\Category\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\Brand\Services\BrandService;
use App\Modules\Category\Requests\CategoryBrandRequest;
use App\Modules\Category\Requests\CategoryCreateRequest;
use App\Modules\Category\Requests\CategoryUpdateRequest;
use App\Modules\Category\Resources\CategoryResource;
use App\Modules\Category\Resources\CategoryTreeResource;
use App\Modules\Category\Services\CategoryService;
use App\Modules\Types\Resources\CategoryType\MinimalCategoryTypeResource;
use App\Modules\Types\Services\CategoryTypeService;
use Exception;
use Illuminate\Support\Facades\DB;

class CategoryController extends BaseController
{

    public $title = 'Category';
    public $base_route = 'admin.categories';
    public $sub_icon = 'file';
    public $module = 'Category::';


    private $view;
    private $categoryService;
    private $brandService;
    private $categoryTypeService;

    public function __construct(
        CategoryService $categoryService,
        BrandService $brandService,
        CategoryTypeService $categoryTypeService
    )
    {
        $this->middleware('permission:View Category List', ['only' => ['index']]);
        $this->middleware('permission:Create Category', ['only' => ['create','store']]);
        $this->middleware('permission:Show Category', ['only' => ['show']]);
        $this->middleware('permission:Update Category', ['only' => ['edit','update']]);
        $this->middleware('permission:Delete Category', ['only' => ['destroy']]);

        $this->view = 'admin.';
        $this->categoryService = $categoryService;
        $this->brandService = $brandService;
        $this->categoryTypeService = $categoryTypeService;

    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $categories = $this->categoryService->getAllCategories();
        return view(Parent::loadViewData($this->module.$this->view.'index'),compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $categories = $this->categoryService->getAllCategories();
        $categoryTypes = $this->categoryTypeService->getAllCategoryTypes();
        return view(Parent::loadViewData($this->module.$this->view.'create'),compact('categories', 'categoryTypes'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(CategoryCreateRequest $request)
    {
        $validated = $request->validated();
        try{
            $category =  $this->categoryService->create($validated);
        }catch(\Exception $exception){
             return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
        return redirect()->back()->with('success', $this->title . ': '. $category->category_name .' Created Successfully');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($ProductWarrantyCode)
    {
        return view('Product::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($categoryCode)
    {
        try{
            $categories = $this->categoryService->getAllCategories();
            $category = $this->categoryService->getCategoryByCode($categoryCode);
            $categoryTypes = $this->categoryTypeService->getAllCategoryTypes();
            $categoryTypeCodes = $category->categoryTypes()->pluck('category_types.category_type_code')->toArray();
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('categories','category', 'categoryTypes', 'categoryTypeCodes'));


    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(CategoryUpdateRequest $request, $categoryCode)
    {

        $validated = $request->validated();
        try{
            $category = $this->categoryService->update($validated, $categoryCode);
            return redirect()->back()->with('success', $this->title . ': '. $category->category_name .' Updated Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }

    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($categoryCode)
    {
        try{
            $category = $this->categoryService->delete($categoryCode);
            return redirect()->back()->with('success', $this->title . ': '. $category->category_name .' Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function getCategoryTypes(Request $request, $categoryCode)
    {

        try{
            $category = $this->categoryService->getCategoryByCode($categoryCode);
            $categoryTypes = $this->categoryTypeService->getCategoryTypesByCategory($category);
            $categoryTypes = MinimalCategoryTypeResource::collection($categoryTypes);
            // if($request->expectsJson())
                return sendSuccessResponse('Data Found!', $categoryTypes);

        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }

    }

}

