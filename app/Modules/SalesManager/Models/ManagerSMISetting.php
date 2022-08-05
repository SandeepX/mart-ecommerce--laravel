<?php

/**
 * Created by PhpStorm.
 * User: Sandeep
 * Date: 22/4/2021
 * Time: 4:47 PM
 */

namespace App\Modules\SalesManager\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\User\Models\User;

use Illuminate\Database\Eloquent\Model;


class ManagerSMISetting extends Model
{
    use ModelCodeGenerator;

    protected $table = 'manager_smi_settings';
    protected $primaryKey = 'msmi_settings_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'msmi_settings_code',
        'salary',
        'terms_and_condition',
        'created_by',
        'updated_by'
    ];

    const RECORDS_PER_PAGE = 20;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->msmi_settings_code = $model->generateMSMISettingCode();
            $model->created_by = getAuthUserCode();
            $model->updated_by = getAuthUserCode();
        });

        static::updating(function ($model) {
            $model->updated_by = getAuthUserCode();
        });
    }

    public function generateMSMISettingCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'MSMIS', '1000', false);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_code');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'user_code');
    }



}

