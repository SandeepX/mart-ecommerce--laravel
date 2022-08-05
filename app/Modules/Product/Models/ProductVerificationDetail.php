<?php

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVerificationDetail extends Model
{
    use SoftDeletes;
    protected $table = 'product_verification_details';
    protected $primaryKey = 'product_verification_detail_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'verification_code',
        'old_verification_status',
        'new_verification_status',
        'old_verification_date',
        'new_verification_date',
        'remarks'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->product_verification_detail_code = $model->generateCode();
        });
    }

    public function generateCode()
    {
        $prefix = 'PV';
        $initialIndex = '00001';
        $productVerificationDetail = self::withTrashed()->latest('id')->first();
        if($productVerificationDetail){
            $codeTobePad = str_replace($prefix,"",$productVerificationDetail->product_verification_detail_code) +1 ;
            $paddedCode = str_pad($codeTobePad, 5, '0', STR_PAD_LEFT);
            $latestCode = $prefix.$paddedCode;
        }else{
            $latestCode = $prefix.$initialIndex;
        }
        return $latestCode;
    }
}