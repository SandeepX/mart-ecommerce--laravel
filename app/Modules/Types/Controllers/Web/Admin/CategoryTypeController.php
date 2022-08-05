<?php

namespace App\Modules\Types\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Types\Requests\CategoryType\CategoryTypeCreateRequest;
use App\Modules\Types\Requests\CategoryType\CategoryTypeUpdateRequest;
use App\Modules\Types\Services\CategoryTypeService;

class CategoryTypeController extends BaseController
{
    public $title = 'Category Type';
    public $base_route = 'admin.category-types';
    public $sub_icon = 'file';
    public $module = 'Types::';


    private $view;
    private $categoryTypeService;


    public function __construct(CategoryTypeService $categoryTypeService)
    {
        $this->middleware('permission:View Category Type List', ['only' => ['index']]);
        $this->middleware('permission:Create Category Type', ['only' => ['create','store']]);
        $this->middleware('permission:Show Category Type', ['only' => ['show']]);
        $this->middleware('permission:Update Category Type', ['only' => ['edit','update']]);
        $this->middleware('permission:Delete Category Type', ['only' => ['destroy']]);

        $this->view = 'admin.category-types.';
        $this->categoryTypeService = $categoryTypeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categoryTypes = $this->categoryTypeService->getAllCategoryTypes();
        return view(Parent::loadViewData($this->module.$this->view.'index'),compact('categoryTypes'));
    }


    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view(Parent::loadViewData($this->module.$this->view.'create'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryTypeCreateRequest $request)
    {
        $validated = $request->validated();
        try{
            $categoryType =  $this->categoryTypeService->storeCategoryType($validated);

        }catch(\Exception $exception){

            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return redirect()->back()->with('success', $this->title . ': '. $categoryType->category_type_name .' Created Successfully');
    }

   


    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($categoryTypeCode)
    {
        try{
            $categoryType = $this->categoryTypeService->findOrFailCategoryTypeByCode($categoryTypeCode);
        }catch(\Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
        return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('categoryType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryTypeUpdateRequest $request,$categoryTypeCode)
    {
        $validated = $request->validated();
        try{
            $categoryType = $this->categoryTypeService->updateCategoryType($validated, $categoryTypeCode);
            return redirect()->back()->with('success', $this->title . ': '. $categoryType->category_type_name .' Updated Successfully');
        }catch (\Exception $exception){
            
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($categoryTypeCode)
    {
        try{
            $categoryType = $this->categoryTypeService->deleteCategoryType($categoryTypeCode);
            return redirect()->back()->with('success', $this->title . ': '. $categoryType->category_type_name .' Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
