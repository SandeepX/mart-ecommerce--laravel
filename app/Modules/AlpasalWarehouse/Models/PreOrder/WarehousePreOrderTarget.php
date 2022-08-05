<?php


namespace App\Modules\AlpasalWarehouse\Models\PreOrder;


use App\Modules\Application\Traits\IsActiveScope;
use App\Modules\Types\Models\StoreType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehousePreOrderTarget extends Model
{

    use SoftDeletes,IsActiveScope;

    protected $table = 'preorder_target';
    protected $primaryKey = 'preorder_target_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'warehouse_preorder_listing_code',
        'store_type_code',
        'target_type',
        'target_value',
        'created_by',
    ];


    public function generateCode()
    {
        $prefix = 'WPT';
        $initialIndex = '1000';
        $preOrder = self::withTrashed()->latest('id')->first();
        if($preOrder){
            $codeTobePad = (int) (str_replace($prefix,"",$preOrder->preorder_target_code)+1 );
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
            $authUserCode = getAuthUserCode();
            $model->preorder_target_code = $model->generateCode();
            $model->created_by = $authUserCode;
        });

    }

  public function storeType()
  {
      return $this->belongsTo(StoreType::class,'store_type_code','store_type_code');
  }

}
