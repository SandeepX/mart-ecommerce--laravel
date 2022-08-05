<?php


namespace App\Modules\Impersonate\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Impersonate extends Model
{
    use ModelCodeGenerator;

    protected $table = 'impersonate_master';
    protected $primaryKey = 'impersonate_master_code';
    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'impersonate_master_code',
        'impersonater_code',
        'impersonatee_type',
        'impersonatee_code',
        'uuid',
        'remark',
        'logged_in_details',
        'logged_in_at',
        'logged_out_at',
        'expires_at',
    ];

    const RECORDS_PER_PAGE = 10;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->impersonate_master_code = $model->generateImpersonaterMasterCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function generateImpersonaterMasterCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'IM', 1000, false);
    }

}


