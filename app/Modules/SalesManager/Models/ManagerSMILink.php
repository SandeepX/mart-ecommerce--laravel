<?php

/**
 * Created by PhpStorm.
 * User: Sandeep
 * Date: 23/4/2021
 * Time: 5:47 PM
 */

namespace App\Modules\SalesManager\Models;


use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\SalesManager\Models\SocialMedia;
use App\Modules\User\Models\User;

use Illuminate\Database\Eloquent\Model;


class ManagerSMILink extends Model
{
    use ModelCodeGenerator;

    protected $table = 'manager_smi_links';
    protected $primaryKey = 'msmi_link_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'msmi_link_code',
        'msmi_code',
        'sm_code',
        'social_media_links',
        'created_by',
        'updated_by'
    ];

    const RECORDS_PER_PAGE = 20;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->msmi_link_code = $model->generateManagerSMILinkCode();
            $model->created_by = getAuthUserCode();
            $model->updated_by = getAuthUserCode();
        });

        static::updating(function ($model) {
            $model->updated_by = getAuthUserCode();
        });
    }

    public function generateManagerSMILinkCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'MSMIL', '1000', false);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_code');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'user_code');
    }

    public function managerSMI()
    {
        return $this->belongsTo(ManagerSMI::class, 'msmi_code', 'msmi_code');
    }

    public function socialMedia()
    {
        return $this->belongsTo(SocialMedia::class, 'sm_code', 'sm_code');
    }


}

