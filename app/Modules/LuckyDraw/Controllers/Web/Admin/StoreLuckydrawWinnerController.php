<?php

namespace App\Modules\LuckyDraw\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\LuckyDraw\Services\StoreLuckydrawService;
use App\Modules\LuckyDraw\Services\StoreLuckydrawWinnerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Exception;

class StoreLuckydrawWinnerController extends BaseController
{

    public $title = 'Store Lucky Draw Winner';
    public $base_route = 'admin.store-luckydraw-winners';
    public $sub_icon = 'file';
    public $module = 'LuckyDraw::';


    private $view='admin.store-luckydraw-winners.';

    private $storeLuckydrawWinnerService,$storeLuckydrawService;



    public function __construct(
        StoreLuckydrawWinnerService $storeLuckydrawWinnerService,
        StoreLuckydrawService $storeLuckydrawService

    )
    {

        $this->storeLuckydrawWinnerService = $storeLuckydrawWinnerService;
        $this->storeLuckydrawService = $storeLuckydrawService;

    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        try{

            $storeLuckydrawWinners = $this->storeLuckydrawWinnerService->getAllStoreLuckydrawWinners();

            return view(Parent::loadViewData($this->module.$this->view.'index'),
                compact('storeLuckydrawWinners'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }

    }

    public function show($storeLuckydrawCode)
    {
        $storeLuckydraw = $this->storeLuckydrawService->findOrFailStoreLuckydrawByCode($storeLuckydrawCode);

        return view(Parent::loadViewData($this->module.$this->view.'show'),compact('storeLuckydraw'));

    }

}

