<?php

namespace App\Modules\ContentManagement\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\ContentManagement\Services\SitePageService;
use App\Modules\ContentManagement\Requests\SitePageRequest;
use Exception;
use Illuminate\Support\Facades\DB;

class SitePageController extends BaseController
{
    public $title = 'Site Page';
    public $base_route = 'admin.site-pages';
    public $sub_icon = 'file';
    public $module = 'ContentManagement::';
    public $view = 'admin.site-setting.';

    
    private $sitePageService;
    public function __construct(SitePageService $sitePageService)
    {
        $this->middleware('permission:View Site Page List', ['only' => ['index']]);
        $this->middleware('permission:Create Site Page', ['only' => ['create','store']]);
        $this->middleware('permission:Show Site Page', ['only' => ['show']]);
        $this->middleware('permission:Update Site Page', ['only' => ['edit','update']]);
        $this->middleware('permission:Delete Site Page', ['only' => ['destroy']]);

        $this->sitePageService = $sitePageService;
    }


    public function index()
    {
        $sitePages = $this->sitePageService->getAllSitePages();
        return view(Parent::loadViewData($this->module.$this->view.'index'),compact('sitePages'));
    }

    public function show($sitePageContentType)
    {
        $sitePage = $this->sitePageService->findSitePageByContentType($sitePageContentType);
        if($sitePage)
            return view(Parent::loadViewData($this->module.$this->view.'show'),compact('sitePage'));
        return view(Parent::loadViewData($this->module.$this->view.'show'));
        
    }

    public function edit($sitePageContentType)
    {
        try{
            $sitePage = $this->sitePageService->findSitePageByContentType($sitePageContentType);
            return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('sitePage'));
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
       
    }

    public function update(SitePageRequest $sitePageRequest, $sitePageContentType)
    {
        $validatedSitePage = $sitePageRequest->validated();
        DB::beginTransaction();
        try{
            $sitePage = $this->sitePageService->findSitePageByContentType($sitePageContentType);
            $sitePage = $this->sitePageService->updateSitePage($sitePage, $validatedSitePage);
            DB::commit();
            return redirect()->back()->with('success', $this->title . ': '. $sitePage->content_type .' Updated Successfully');

        }catch(Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();

        }
    }
}