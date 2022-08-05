<?php

namespace App\Modules\AlpasalWarehouse\Models\Setting;


use App\Modules\Accounting\Models\FiscalYear;
use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceSetting extends Model
{

    use ModelCodeGenerator;
    use SoftDeletes;
    protected $table = 'settings_warehouse_invoice';
    protected $primaryKey = 'setting_warehouse_invoice_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'order_type',
        'warehouse_code',
        'starting_number',
        'ending_number',
        'fiscal_year_code',
        'next_number',
        'created_by',
        'updated_by'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->setting_warehouse_invoice_code = $model->generateSettingWarehouseInvoiceCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

        static::deleting(function ($model) {
            $model->deleted_by = getAuthUserCode();
            $model->save();
        });

    }

    public function generateSettingWarehouseInvoiceCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'SWIC', '1000', true);
    }

    public function fiscalyear(){
        return $this->belongsTo(FiscalYear::class,'fiscal_year_code','fiscal_year_code');
    }

}
