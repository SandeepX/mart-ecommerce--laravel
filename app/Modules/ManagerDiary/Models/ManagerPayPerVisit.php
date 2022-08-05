<?php

namespace App\Modules\ManagerDiary\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\SalesManager\Models\Manager;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ManagerPayPerVisit extends Model
{
    use ModelCodeGenerator;
    protected $table = 'manager_pay_per_visits';
    protected $primaryKey = 'manager_pay_per_visit_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'manager_pay_per_visit_code',
        'manager_code',
        'amount',
        'created_by',
        'updated_by'
    ];

    CONST PAGINATE_BY = 10;

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->manager_pay_per_visit_code = $model->generateCode();
        });
        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });
    }

    public function generateCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'MPPVC', 1000, false);
    }

    public function manager(){
        return $this->belongsTo(Manager::class,'manager_code','manager_code');
    }

}
