<?php

namespace App\Modules\Career\Controllers\Web\Admin;
use App\Modules\Application\Controllers\BaseController;

use App\Modules\Career\Models\Career;
use App\Modules\Career\Requests\CareerCreateRequest;
use App\Modules\Career\Requests\CareerUpdateRequest;
use App\Modules\Career\Services\CareerService;

class CareerController extends BaseController
{
    public $title= "Career";
    public $base_route= 'admin.careers.';
    public $module = 'Career::';

    private $view='admin.career.';
    private $careerService;

    public function __construct(CareerService $careerService){

        $this->careerService =$careerService;
    }

    public function index()
    {
        $career =$this->careerService->getAllCareer();
        return view(Parent::loadViewData($this->module.$this->view.'index'),compact('career'));
    }

    public function create()
    {
        try{
            return view(Parent::loadViewData($this->module.$this->view.'create'));
        }catch(\Exception $exception){
            return redirect()->route($this->base_route.'index')->with('danger', $exception->getMessage());
        }
    }

    public function store(CareerCreateRequest $request)
    {
        $validatedData=$request->validated();
        try{
            $this->careerService->createCareer($validatedData);
            return redirect()->back()->with('success', $this->title .' Created Successfully');
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    public function show(Career $career)
    {
        return redirect()->route($this->base_route.'index');
    }

    public function edit(Career $career)
    {
        try{
            $career = $this->careerService->getCareerByCode($career->career_code);
            return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('career'));
        }
        catch (Exception $exception){
            return redirect()->route($this->base_route.'index')->with('danger',$exception->getMessage());
        }
    }

    public function update(CareerUpdateRequest $request, $careerCode)
    {
        $validatedData=$request->validated();
        try{
            $this->careerService->careerServiceUpdate($validatedData,$careerCode);
            return redirect()->back()->with('success', $this->title .' Updated Successfully');

        }catch(Exception $exception){

            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    public function destroy(Career $career)
    {
        try{
            $this->careerService->deleteCareer($career->career_code);
            return redirect()->back()->with('success', $this->title .' Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
