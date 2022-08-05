<?php


namespace App\Modules\Store\Models\StorePackageTypes;


use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Types\Models\StoreType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StorePackageHistory extends Model
{
    use ModelCodeGenerator;
    protected $table = 'store_package_history';
    protected $primaryKey = 'store_package_history_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'store_package_history_code',
        'store_code',
        'store_type_code',
        'store_type_package_history_code',
        'from_date',
        'to_date',
        'remarks',
        'created_by',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->store_package_history_code = $model->generateCode();
        });

    }

    public function generateCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'SPHC', '1000', false);
    }

    public function storeType(){
        return $this->belongsTo(StoreType::class,'store_type_code','store_type_code');
    }
    public function storeTypePackageHistory(){
        return $this->belongsTo(StoreTypePackageHistory::class,'store_type_package_history_code','store_type_package_history_code');
    }




}
