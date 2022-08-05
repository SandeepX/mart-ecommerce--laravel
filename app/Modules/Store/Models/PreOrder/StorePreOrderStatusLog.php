<?php


namespace App\Modules\Store\Models\PreOrder;


use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;

class StorePreOrderStatusLog extends Model
{
    use ModelCodeGenerator;

    protected $table = 'store_preorder_status_log';

    protected $primaryKey = 'store_preorder_status_log_code';
    public $incrementing = false;
    protected $keyType = 'string';

    const MODEL_PREFIX="SPSL";

    protected $fillable = [
        'store_preorder_code',
        'status',
        'remarks',
        'updated_by'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->store_preorder_status_log_code = $model->generateCode();
            $model->updated_by = getAuthUserCode();
        });
    }

    public function generateCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this,$this->primaryKey,'SPSL',"1000",false);
        // return $this->generateModelCode($this, $this->primaryKey, 'SOSL', '00001', 5);
    }

    public function updatedBy(){
        return $this->belongsTo(User::class,'updated_by','user_code');
    }

    public function storePreOrder(){
        return $this->belongsTo(StorePreOrder::class,'store_preorder_code','store_preorder_code');
    }
}
