<?php


namespace App\Modules\SalesManager\Controllers\Web\SocialMedia;


use App\Modules\Application\Controllers\BaseController;
use App\Modules\SalesManager\Requests\SocialMediaRequest;
use App\Modules\SalesManager\Services\SocialMedia\SocialMediaService;


class SocialMediaController extends BaseController
{
    public $title = 'Social Media';
    public $base_route = 'admin.social-media';
    public $sub_icon = 'file';
    public $module = 'SalesManager::';
    public $view = 'admin.social-media.';

    private $socialMediaService;

    public function __construct(SocialMediaService $socialMediaService){
        $this->socialMediaService = $socialMediaService;
    }

    public function index()
    {
        try{
            $socialMediaDetail = $this->socialMediaService->getAllSocialMedias();
            return view(Parent::loadViewData($this->module . $this->view . 'index'),
                compact('socialMediaDetail')
            );
        }catch(\Exception $exception){
            return redirect()->route('admin.dashboard')->with('danger', $exception->getMessage());
        }

    }

    public function create()
    {
        try{
            return view(Parent::loadViewData($this->module . $this->view . 'create'));
        }catch(\Exception $e){
            return redirect()->back()->with('danger', $e->getMessage());
        }

    }

    public function store(SocialMediaRequest $request)
    {
       try{
           $validatedData = $request->validated();
           $this->socialMediaService->store($validatedData);
           return redirect()->back()->with('success', $this->title . ':  Created Successfully');

       }catch(\Exception $e){
           return redirect()->back()->with('danger', $e->getMessage());
       }

    }

    public function toggleEnableStatusForSMI($SMCode)
    {
        try{
            $socialMedia = $this->socialMediaService->toggleEnableStatusForSMI($SMCode);
            return redirect()->route('admin.social-media.index')->with('success',' Enable Status changed Successfully');
        }catch(\Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function edit($SMCode)
    {
        try{
            $socialMediaDetail = $this->socialMediaService->getSocialMediaByCode($SMCode);
            return view(Parent::loadViewData($this->module . $this->view . 'edit'),
                compact('socialMediaDetail')
            );

        }catch(\Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function update(SocialMediaRequest $request, $socialMediaCode)
    {
        $validatedData = $request->validated();
        try{
            $socialMedia =  $this->socialMediaService->update($validatedData,$socialMediaCode);
            return redirect()->back()->with('success', $this->title . ':  updated Successfully');
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    public function destroy($SMCode)
    {
        try{
            $socialMedia =  $this->socialMediaService->deleteSocialMedia($SMCode);

            return redirect()->back()->with('success', ' Social Media Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }

    }

}
