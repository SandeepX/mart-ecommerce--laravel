<?php


namespace App\Modules\SupportAdmin\Controllers\stores;


use App\Modules\Application\Controllers\BaseController;
use App\Modules\Store\Services\StoreService;
use App\Modules\SupportAdmin\Requests\SupportAdminSearchStoreRequest;
use Exception;


class StoreDetailForSupportAdminController extends BaseController
{

    public $title = 'Store Detail For Admin Support';
    public $base_route = 'support-admin.';
    public $sub_icon = 'file';
    public $module = 'SupportAdmin::';

    private $view = 'stores.';

    private $storeService;


    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
    }

    public function showStoreSearchForm()
    {
        return view(Parent::loadViewData($this->module . $this->view . 'index'));
    }

    public function searchStore(SupportAdminSearchStoreRequest $request)
    {
        $validatedData = $request->validated();
        try{
            $checkFormData = $this->checkValidatedData($validatedData);
            if($checkFormData == false) {
                throw new Exception ('Please input data in at least one field to find Store',400);
            }
            $storeDetail = $this->storeService->findStoreForSupportAdminByFormData($validatedData);
            return view(Parent::loadViewData($this->module.$this->view.'show-store-detail'),
                compact('storeDetail'));
        }catch(Exception $e){
            return redirect()->back()->with('danger',$e->getMessage());
        }
    }

    public function checkValidatedData($array)
    {
        foreach ($array as $key => $item) {
            if ($item) return true;
        }
        return false;
    }




}
