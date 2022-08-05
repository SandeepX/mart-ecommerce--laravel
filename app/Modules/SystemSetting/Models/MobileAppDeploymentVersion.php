<?php

namespace App\Modules\SystemSetting\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;

class MobileAppDeploymentVersion extends Model
{
    protected $table = 'mobile_app_deployment_version';
    protected $fillable = [
        'manager_version',
        'manager_build_number',
        'store_version',
        'store_build_number'
    ];


}
