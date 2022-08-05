<?php

namespace App\Modules\Profile\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Profile\Services\UserProfileService;

class AdminProfileController extends BaseController
{
    public $title = 'Profile';
    public $base_route = 'admin.profile.';
    public $sub_icon = 'file';
    public $module = 'Profile::';

    private $userProfileService;

    public function __construct(UserProfileService $userProfileService)
    {
        $this->view = 'admin.profile';
        $this->userProfileService = $userProfileService;
    }
    
    public function showAdminProfilePage()
    {
        return view(Parent::loadViewData($this->module.$this->view.'.show'));
    }

}