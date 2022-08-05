<?php

/**
 * Created by PhpStorm.
 * User: Sandeep
 * Date: 25/4/2021
 * Time: 1:30 PM
 */


namespace App\Modules\SalesManager\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\User\Models\User;

use Illuminate\Database\Eloquent\Model;


class SMIManagerAttendance extends Model
{
    use ModelCodeGenerator;

    protected $table = 'smi_manager_attendances';
    protected $primaryKey = 'msmi_attendance_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'msmi_attendance_code',
        'msmi_code',
        'attendance_date',
        'status',
        'remarks',
        'created_by',
        'updated_by'
    ];

    const RECORDS_PER_PAGE = 20;

    const STATUS = ['absent','present'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->msmi_attendance_code = $model->generateSMIManagerAttendanceCode();
            $model->created_by = getAuthUserCode();
            $model->updated_by = getAuthUserCode();
        });

        static::updating(function ($model) {
            $model->updated_by = getAuthUserCode();
        });
    }

    public function generateSMIManagerAttendanceCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'SMIMA', '1000', false);
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


}


