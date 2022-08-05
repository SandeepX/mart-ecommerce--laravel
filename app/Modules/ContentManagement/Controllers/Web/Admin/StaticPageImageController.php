<?php


namespace App\Modules\ContentManagement\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\ContentManagement\Requests\StaticPageImageRequest;
use App\Modules\ContentManagement\Services\StaticPageImageService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class StaticPageImageController extends BaseController
{
    public $title = 'Site Page Image';
    public $base_route = 'admin.site-page-Image';
    public $sub_icon = 'file';
    public $module = 'ContentManagement::';
    public $view = 'admin.static-page-image.';


    private $staticPageImageService;

    public function __construct(StaticPageImageService $staticPageImageService)
    {
        $this->middleware('permission:View Static Page Image List', ['only' => ['index']]);
        $this->middleware('permission:Create Static Page Image', ['only' => ['create', 'store']]);
        $this->middleware('permission:Show Static Page Image', ['only' => ['show']]);
        $this->middleware('permission:Update Static Page Image', ['only' => ['edit', 'update']]);
        $this->middleware('permission:Delete Static Page Image', ['only' => ['destroy']]);

        $this->staticPageImageService = $staticPageImageService;
    }

    public function index()
    {
        $staticPageImage =  $this->staticPageImageService->getAllSitePagesImageByGroupBy();
        return view(Parent::loadViewData($this->module . $this->view . 'index'),compact('staticPageImage'));
    }

    public function create()
    {
        return view(Parent::loadViewData($this->module.$this->view.'create'));
    }

    public function store(StaticPageImageRequest $request)
    {
        $validatedData = $request->validated();
        try{
            $staticPageImage =  $this->staticPageImageService->storeStaticPageImage($validatedData);
            return redirect()->back()->with('success', $this->title . ':  Created Successfully');
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    public function show($page_name)
    {
        $showDetail =  $this->staticPageImageService->getAllImagesOfStaticPageByPageName($page_name);
        return view(Parent::loadViewData($this->module . $this->view . 'show'),compact('showDetail'));

    }

    public function edit($SPICode)
    {
        $editDetail =  $this->staticPageImageService->findorFailForUpdate($SPICode);
        return view(Parent::loadViewData($this->module . $this->view . 'edit'),compact('editDetail'));
    }

    public function update(StaticPageImageRequest $request, $SPICode)
    {
        $validated = $request->validated();
        try{
            $staticPageImageUpdate =  $this->staticPageImageService->updateStaticPageImage($validated,$SPICode);
            return redirect()->back()->with('success', $this->title . ':  Updated Successfully');
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    public function deleteAllImage($page_name)
    {

        try{
            $this->staticPageImageService->deleteStaticPageImage($page_name);
            return redirect()->route('admin.static-page-images.index')->with('success', 'Whole static page Image Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function deleteSingleImage($SPICode)
    {

        try{
            $this->staticPageImageService->deleteStaticPageImageSingleImage($SPICode);
            return redirect()->route('admin.static-page-images.index')->with('success', 'Static page Image Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

}
