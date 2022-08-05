<?php

namespace App\Modules\Admin\Controllers;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Admin\Services\AdminProfileService;

class AdminProfileController extends BaseController
{
    public $title = 'User Profile';
    public $base_route = 'admin.profile';
    public $sub_icon = 'file';
    public $module = 'Admin::';


    private $view;
    private $adminProfileService;


    public function __construct(AdminProfileService $adminProfileService)
    {
        $this->view = 'profile.';
        $this->adminProfileService = $adminProfileService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAdminProfile()
    {
        $user = auth()->user;
        return view(Parent::loadViewData($this->module.$this->view.'index'),compact('user'));
    }


}
