<?php


namespace App\Modules\B2cCustomer\Controllers\Web\Admin;


use App\Modules\Application\Controllers\BaseController;
use App\Modules\B2cCustomer\Helper\UserB2CFilterHelper;
use App\Modules\B2cCustomer\Services\B2CUserService;
use Illuminate\Http\Request;
use Exception;

class B2CUserController extends BaseController
{

    public $title = 'B2C Customer';
    public $base_route = 'admin.b2c-user';
    public $sub_icon = 'file';
    public $module = 'B2cCustomer::';
    private $view = 'admin.';

    private $userB2CService;

    public function __construct(
        B2CUserService $userB2CService
    )
    {
        $this->middleware('permission:View Customer Lists', ['only' => ['index']]);
        $this->middleware('permission:Show Customer', ['only' => ['show']]);

        $this->userB2CService = $userB2CService;
    }

    public function index(Request $request)
    {
        try {
            $filterParameters = [
                'user_type' => ['b2c-customer'],
                'user_name' => $request->user_name,
                'email' => $request->email,

            ];
            $with = [
                'userType'
            ];
            $userB2C = UserB2CFilterHelper::filterPaginatedB2CUser($filterParameters, 10, $with);
            return view(Parent::loadViewData($this->module . $this->view . 'index'), compact('userB2C', 'filterParameters'));
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function show($userCode)
    {
        try {
            $userB2C = $this->userB2CService->findOrFailB2CUserByCodeWith($userCode, ['userB2CRegistrationStatus', 'userDocs']);

            if (!$userB2C->isB2CUser()) {
                throw new Exception('Cannot Show users other than B2C');
            }

            $userDocs = $userB2C->userDocs;
            $userRegistrationStatus = $userB2C->userB2CRegistrationStatus;
        } catch (\Exception $ex) {
            return redirect()->back()->with('danger', $ex->getMessage());
        }

        return view(Parent::loadViewData($this->module . $this->view . 'show'),
            compact('userB2C', 'userDocs', 'userRegistrationStatus'));
    }

    public function changeRegistartionStatus($userCode)
    {
        dd($userCode);
    }

}


