<?php
/**
 * Created by PhpStorm.
 * User: Sandeep
 * Date: 10/22/2021
 * Time: 1:12 PM
 */
namespace App\Modules\Dashboard\Controllers\SupportAdmin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Store\Models\Store;
use App\Modules\Store\Services\StoreService;
use Illuminate\Http\Request;


class AdminSupportDashboardController extends BaseController
{
    protected $module = 'Dashboard::';
    protected $view = 'support-admin.';

    public $storeService;

    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
//        $this->middleware('permission:View Support Admin Dashboard', ['only' => 'index']);

    }

    public function index(Request $request)
    {
//        $storesCount = $this->storeService->getAllStores()->count();
        return view($this->module . $this->view . 'index');
    }
}
