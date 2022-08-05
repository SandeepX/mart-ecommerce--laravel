<?php

/**
 * Created by PhpStorm.
 * User: Sandeep
 * Date: 23/4/2021
 * Time: 12:47 PM
 */

namespace App\Modules\SalesManager\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\SystemSetting\Models\SocialMedia;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManagerSMI extends Model
{
    use SoftDeletes;

    use ModelCodeGenerator;

    protected $table = 'manager_smi';
    protected $primaryKey = 'msmi_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'msmi_code',
        'manager_code',
        'status',
        'is_active',
        'allow_edit',
        'edit_allowed_by',
        'allow_edit_remarks',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    const RECORDS_PER_PAGE = 10;
    const STATUS = ['pending','approved','rejected'];

    private const ACTIVE = 1;
    private const INACTIVE = 0;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->msmi_code = $model->generateManagerSMICode();
            $model->created_by = getAuthUserCode();
            $model->updated_by = getAuthUserCode();
        });

        static::updating(function ($model) {
            $model->updated_by = getAuthUserCode();
        });

        static::deleting(function ($model) {
            $model->deleted_by = getAuthUserCode();
        });
    }

    public function generateManagerSMICode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'MSMI', '1000', true);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_code');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'user_code');
    }

    public function editAllowedBy()
    {
        return $this->belongsTo(User::class, 'allow_edit_remarks', 'user_code');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by', 'user_code');
    }

    public function manager()
    {
        return $this->belongsTo(Manager::class, 'manager_code', 'manager_code');
    }

    public function managerLinks()
    {
        return $this->hasMany(ManagerSMILink::class, 'msmi_code', 'msmi_code');
    }

    public function canManagerUpdateSMIDetail()
    {
        if($this->is_active == 1){
            if($this->allow_edit == 1 || $this->status == 'rejected') {
                return true;
            }
        }
        return false;
    }

}



