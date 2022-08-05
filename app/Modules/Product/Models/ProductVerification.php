<?php

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVerification extends Model
{
    use SoftDeletes;
    protected $table = 'product_verification';
    protected $primaryKey = 'verification_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'product_code',
        'user_code',
        'verification_status',
        'verification_date',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->verification_code = $model->generateCode();
        });
    }

    public function generateCode()
    {
        $prefix = 'PV';
        $initialIndex = '00001';
        $productVerification = self::withTrashed()->latest('id')->first();
        if($productVerification){
            $codeTobePad = str_replace($prefix,"",$productVerification->verification_code) +1 ;
            $paddedCode = str_pad($codeTobePad, 5, '0', STR_PAD_LEFT);
            $latestCode = $prefix.$paddedCode;
        }else{
            $latestCode = $prefix.$initialIndex;
        }
        return $latestCode;
    }

    public function verificationDetails()
    {
        return $this->hasMany(ProductVerificationDetail::class, 'verification_code');
    }
}