<?php

namespace App\Modules\Store\Models\PreOrder;

use App\User;
use Illuminate\Database\Eloquent\Model;

class StorePreOrderEarlyFinalization extends Model
{

    protected $table = 'store_preorder_early_finalization';
    protected $primaryKey = 'id';

    protected $fillable = [
        'store_preorder_code',
        'early_finalization_date',
        'early_finalization_remarks',
        'early_finalized_by',
        'created_by',
        'updated_by'
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->created_by = $authUserCode;
            $model->updated_by = $authUserCode;
        });
       static::updating(function ($model) {
             $authUserCode = getAuthUserCode();
             $model->updated_by = $authUserCode;
         });
    }

    public function storePreOrder(){
        return $this->belongsTo(StorePreOrder::class,'store_preorder_code','storte_preorder_code');
    }

    public function earlyFinalizedBy(){
        return $this->belongsTo(User::class,'early_finalized_by','user_code');
    }

    public function createdBy(){
        return $this->belongsTo(User::class,'created_by','user_code');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class,'updated_by','user_code');
    }


}
