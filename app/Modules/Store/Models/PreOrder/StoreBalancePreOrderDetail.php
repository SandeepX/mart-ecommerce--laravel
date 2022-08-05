<?php


namespace App\Modules\Store\Models\PreOrder;


use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Store\Models\Payments\StoreBalanceMaster;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StoreBalancePreOrderDetail extends Model
{
    use ModelCodeGenerator;

    protected $table = 'store_balance_preorder_details';
    protected $primaryKey = 'store_preorder_balance_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'store_balance_master_code',
        'store_preorder_code'
    ];


    public function generateCode()
    {
        $prefix = 'SBPOD';
        $initialIndex = '1000';
        $preOrderBalanceDetail = self::latest('id')->first();
        if($preOrderBalanceDetail){
            $codeTobePad = (int) (str_replace($prefix,"",$preOrderBalanceDetail->store_preorder_balance_code) +1 );
            //$paddedCode = str_pad($codeTobePad, 5, '0', STR_PAD_LEFT);
            $latestCode = $prefix.$codeTobePad;
        }else{
            $latestCode = $prefix.$initialIndex;
        }
        return $latestCode;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->store_preorder_balance_code = $model->generateCode();
        });


    }
    public function storeBalanceMaster(){
        return $this->belongsTo(StoreBalanceMaster::class,'store_balance_master_code','store_balance_master_code');
    }
    public function storePreOrder(){
        return $this->belongsTo(StorePreOrder::class,'store_preorder_code','store_preorder_code');
    }

}
