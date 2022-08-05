<?php

namespace App\Modules\ContentManagement\Controllers\Web\Admin;

use App\Modules\ContentManagement\Requests\AboutUsCreateRequest;
use App\Modules\ContentManagement\Requests\AboutUsUpdateRequest;
use App\Modules\ContentManagement\Services\AboutUsService;
use App\Modules\Application\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

class AboutUsController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public $title = 'About Us';
    public $base_route = 'admin.about-us';
    public $sub_icon = 'file';
    public $module = 'ContentManagement::';
    public $view = 'admin.about-us.';
    private $aboutUsService;
    public function __construct(AboutUsService $aboutUsService){
        $this->aboutUsService =$aboutUsService;
    }
    public function index()
    {
        try{
            $aboutus = $this->aboutUsService->getAllAboutUs();
            return view(Parent::loadViewData($this->module.$this->view.'index'),compact('aboutus'));
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function create()
    {
        return view(Parent::loadViewData($this->module.$this->view.'create'));
    }

    public function store(AboutUsCreateRequest $request)
    {
        $validatedData = $request->validated();
        try{
            $this->aboutUsService->storeAboutUs($validatedData);
            return redirect()->back()->with('success', 'AboutUs Created Successfully');
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    public function show($aboutUsCode)
    {
        $aboutUs = $this->aboutUsService->findOrFailAboutUsByCode($aboutUsCode);
        return view(Parent::loadViewData($this->module.$this->view.'show'),compact('aboutUs'));
    }

    public function edit($aboutUs_Code)
    {

        try{
            $aboutUs = $this->aboutUsService->findOrFailAboutUsByCode($aboutUs_Code);
//            dd($aboutUs->company_name);
            return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('aboutUs'));
        }catch (Exception $ex){
            return redirect()->back()->with('danger',$ex->getMessage());
        }
    }

    public function update(AboutUsUpdateRequest $request, $aboutUsCode)
    {
//        dd($request);
        $validatedData = $request->validated();
        try{
            $this->aboutUsService->updateAboutUs($validatedData, $aboutUsCode);
            return redirect()->back()->with('success', 'About Us Updated Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }


    public function destroy($aboutUsCode)
    {
        try{
             $this->aboutUsService->deleteAboutUs($aboutUsCode);
            return redirect()->back()->with('success', $this->title .' About Us Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
