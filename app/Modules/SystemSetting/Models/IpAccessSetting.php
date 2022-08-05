<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/4/2020
 * Time: 1:47 PM
 */

namespace App\Modules\SystemSetting\Models;


use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class IpAccessSetting extends Model
{

    use ModelCodeGenerator;

    protected $table = 'ip_accesses';
    protected $primaryKey = 'ip_access_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ip_access_code',
        'ip_name',
        'ip_address',
        'is_allowed',
        'created_by',
        'updated_by'
    ];

    const RECORDS_PER_PAGE=10;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->ip_access_code = $model->generateIpAccessCode();
            $model->created_by = getAuthUserCode();
            $model->updated_by = getAuthUserCode();
        });

        static::updating(function ($model) {
            $model->updated_by = getAuthUserCode();
        });

    }

    public function scopeAllowed($query){
        return $query->where('is_allowed',1);
    }

    public function generateIpAccessCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'IPA', '1000', false);
    }


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_code');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'user_code');
    }

    public function isAllowed(){

        if ($this->is_allowed == 1){
            return true;
        }

        return false;
    }


}