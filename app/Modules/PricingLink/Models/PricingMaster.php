<?php


namespace App\Modules\PricingLink\Models;


use App\Modules\AlpasalWarehouse\Models\Warehouse;
use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use Exception;
class PricingMaster extends Model
{
    use ModelCodeGenerator;
    protected $table = 'pricing_master';
    protected $primaryKey = 'pricing_master_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'pricing_master_code',
        'warehouse_code',
        'link',
        'link_code',
        'password',
        'expires_at',
        'is_active',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->pricing_master_code = $model->generatePricingMasterCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });
    }

    public function generatePricingMasterCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'PMC', '1000', false);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class,'warehouse_code','warehouse_code');
    }
    public function isPastExpiresTime(){
        $currentTime=Carbon::now('Asia/Kathmandu')->toDateTimeString();
        if ($currentTime > $this->expires_at){
            return true;
        }
        return false;
    }
}
