<?php

namespace App\Modules\Accounting\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class FiscalYear extends Model
{
    use ModelCodeGenerator;
    protected $table = 'fiscal_years';
    protected $primaryKey = 'fiscal_year_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'fiscal_year_name',
        'is_closed',
        'created_by',
        'updated_by'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->fiscal_year_code = $model->generateFiscalYearCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function generateFiscalYearCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'FC', '1', false);
    }


}
