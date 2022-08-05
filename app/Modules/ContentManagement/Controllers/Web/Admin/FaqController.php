<?php 

namespace App\Modules\ContentManagement\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\ContentManagement\Requests\FaqCreateRequest;
use App\Modules\ContentManagement\Requests\FaqUpdateRequest;
use App\Modules\ContentManagement\Services\FaqService;
use Exception;

class FaqController extends BaseController
{
    public $title = 'FAQ';
    public $base_route = 'admin.faqs';
    public $sub_icon = 'file';
    public $module = 'ContentManagement::';
    public $view = 'admin.faq.';

    private $faqService;
    public function __construct(FaqService $faqService)
    {
        $this->middleware('permission:View Faq List', ['only' => ['index']]);
        $this->middleware('permission:Create Faq', ['only' => ['create','store']]);
        $this->middleware('permission:Show Faq', ['only' => ['show']]);
        $this->middleware('permission:Update Faq', ['only' => ['edit','update']]);
        $this->middleware('permission:Delete Faq', ['only' => ['destroy']]);

        $this->faqService = $faqService;
    }

    public function index()
    {
        try{
            $faqs = $this->faqService->getAllFaqs();
            return view(Parent::loadViewData($this->module.$this->view.'index'),compact('faqs'));
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
       
    }

    public function create(){
        return view(Parent::loadViewData($this->module.$this->view.'create'));

    }

    public function store(FaqCreateRequest $faqRequest)
    {
        $validatedFaq = $faqRequest->validated();
        try{
            $this->faqService->storeFaq($validatedFaq);
            return redirect()->back()->with('success', 'Faq Created Successfully');
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    public function edit($faqCode)
    {
        try{
            $faq = $this->faqService->findFaqByCode($faqCode);
            return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('faq'));
        }catch (\Exception $ex){
            return redirect()->back()->with('danger',$ex->getMessage());
        }
    }

    public function update(FaqUpdateRequest $faqRequest, $faqCode)
    {
        $validatedFaq = $faqRequest->validated();
        try{
           $this->faqService->updateFaq($validatedFaq, $faqCode);
           return redirect()->back()->with('success', 'Faq Updated Successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    public function destroy($faqCode)
    {
        try{
            $this->faqService->deleteFaq($faqCode);
           return redirect()->back()->with('success', 'Faq Updated Successfully');
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}