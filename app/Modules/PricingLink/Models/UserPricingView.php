<?php


namespace App\Modules\PricingLink\Models;


use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Location\Models\LocationHierarchy;
use App\Modules\Location\Traits\LocationHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use Exception;
class UserPricingView extends Model
{
    use ModelCodeGenerator,LocationHelper;
    protected $table = 'user_pricing_view';
    protected $primaryKey = 'user_pricing_view_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'user_pricing_view_code',
        'pricing_master_code',
        'mobile_number',
        'full_name',
        'location_code',
        'is_verified',
        'otp_code',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->user_pricing_view_code = $model->generatePricingMasterCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });
    }

    public function generatePricingMasterCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'UPVC', '1000', false);
    }

    public function location(){
        return $this->belongsTo(LocationHierarchy::class, 'location_code');
    }
    public function pricingLink(){
        return $this->belongsTo(PricingMaster::class, 'pricing_master_code');
    }
}
