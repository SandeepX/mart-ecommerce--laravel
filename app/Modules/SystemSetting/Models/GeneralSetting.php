<?php

namespace App\Modules\SystemSetting\Models;


use App\Modules\Bank\Models\Bank;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    protected $table = 'general_settings';
    protected $fillable = [
        'logo',
        'favicon',
        'admin_sidebar_logo',
        'full_address',
        'primary_contact',
        'secondary_contact',
        'primary_bank_name',
        'primary_bank_account_number',
        'primary_bank_branch',
        'secondary_bank_name',
        'secondary_bank_account_number',
        'secondary_bank_branch',
        'company_email',
        'company_brief',
        'facebook',
        'twitter',
        'instagram',
        'is_maintenance_mode',
        'ip_filtering',
        'sms_enable',
        'updated_by'
    ];

    public $uploadFolder = 'uploads/general-setting/';


    public function isMaintenanceModeOn(){
       return $this->is_maintenance_mode ? true : false;
    }

    public function isIpFilteringEnabled(){
        if ($this->ip_filtering){
            return true;
        }

        return false;
    }

}
