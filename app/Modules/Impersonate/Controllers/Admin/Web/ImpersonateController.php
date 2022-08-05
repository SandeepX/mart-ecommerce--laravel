<?php


namespace App\Modules\Impersonate\Controllers\Admin\Web;


use App\Modules\Application\Controllers\BaseController;
use App\Modules\Impersonate\Services\ImpersonateService;
use Illuminate\Support\Facades\Redirect;

class ImpersonateController extends BaseController
{
    public $impersonateService;

    public function __construct(ImpersonateService $impersonateService)
    {
        $this->impersonateService = $impersonateService;
    }

    public function impersonateStore($storeCode)
    {
       try{
            $impersonate =  $this->impersonateService->store($storeCode);
           // return redirect()->to('http://allpasal.com?lgid='.$impersonate);
           return redirect()->away('https://allpasal.com/?lgid='.$impersonate);
       }catch(\Exception $exception){
           return redirect()->back()->with('danger',$exception->getMessage());
       }
    }
}
