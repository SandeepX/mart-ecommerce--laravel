<?php

namespace App\Modules\Bank\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Store\Models\BalanceReconciliation\StoreBalanceReconciliation;

class Bank extends Model
{
    use SoftDeletes;

    protected $table = 'banks';

    protected $primaryKey = 'bank_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'bank_name',
        'slug',
        'bank_code',
        'bank_logo',
        'remarks',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->bank_code = $model->generateCode();
            $model->created_by = $authUserCode;
            $model->updated_by = $authUserCode;
        });

        static::updating(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->updated_by = $authUserCode;
        });

        static::deleting(function ($model) {
            $model->deleted_by = getAuthUserCode();
            $model->save();
        });
    }

    public function generateCode()
    {
        $prefix = 'BNK';
        $initialIndex = '1000';
        $bank= self::withTrashed()->latest('id')->first();
        if($bank){
            $codeTobePad = (int) (str_replace($prefix,"",$bank->bank_code) +1 );
           // $paddedCode = str_pad($codeTobePad, 5, '0', STR_PAD_LEFT);
            $latestCode = $prefix.$codeTobePad;
        }else{
            $latestCode = $prefix.$initialIndex;
        }
        return $latestCode;
    }

    public function balanceReconciliation()
    {
       return $this->hasMany(StoreBalanceReconciliation::class,'bank_code','balance_reconciliation_code');
    }

}
