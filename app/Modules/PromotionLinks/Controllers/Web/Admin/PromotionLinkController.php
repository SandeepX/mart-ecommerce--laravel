<?php

namespace App\Modules\PromotionLinks\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\PromotionLinks\Models\PromotionLink;
use App\Modules\PromotionLinks\Requests\PromotionLinkCreateRequest;
use App\Modules\PromotionLinks\Requests\PromotionLinkUpdateRequest;
use App\Modules\PromotionLinks\Services\PromotionLinkService;
use Exception;

class PromotionLinkController extends BaseController
{
    public $title = 'Promotion Links';
    public $base_route = 'admin.promotion-links.';
    public $sub_icon = 'file';
    public $module = 'PromotionLinks::';
    public $view = 'admin.promotion-links';

    private $promotionLinkService;

    public function __construct(PromotionLinkService $promotionLinkService)
    {
        $this->promotionLinkService =$promotionLinkService;
    }

    public function index(){
        try{
            $promotionLinks = $this->promotionLinkService->getAllPromotionLinks(
                PromotionLink::PAGINATE_BY
            );
            return view(Parent::loadViewData($this->module.$this->view.'.index'),compact('promotionLinks'));
        }catch (Exception $exception){
            return redirect()->route('admin.dashboard')->with('danger', $exception->getMessage())->withInput();
        }
    }

    public function create(){
        return view(Parent::loadViewData($this->module.$this->view.'.create'));
    }

    public function show($id){
        return redirect()->route('admin.promotion-links.index');
    }

    public function store(PromotionLinkCreateRequest $promotionLinkCreateRequest){
        try{
            $validatedData = $promotionLinkCreateRequest->validated();

            $promotionLink = $this->promotionLinkService->storePromotionLink($validatedData);

            return redirect()->back()->with('success', $this->title . ': '. $promotionLink->name .' Created Successfully');
        }catch (Exception $exception){
          return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    public function edit($id){
        try{
            $promotionLink = $this->promotionLinkService->findOrFailPromotionLinkByID($id);
            return view(Parent::loadViewData($this->module.$this->view.'.edit'),compact('promotionLink'));
        }catch (\Exception $ex){
            return redirect()->back()->with('danger',$ex->getMessage());
        }
    }

    public function update(PromotionLinkUpdateRequest $request,$id){
        try{
            $validated = $request->validated();
            $promotionLink = $this->promotionLinkService->updatePromotionLink($validated, $id);
            return redirect()->back()->with('success', $this->title . ': '. $promotionLink->name .' Updated Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    public function destroy($id){
        try{
            $promotionLink = $this->promotionLinkService->deletePromotionLink($id);
            return redirect()->back()->with('success', $this->title . ': '. $promotionLink->name .' Link Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

}
