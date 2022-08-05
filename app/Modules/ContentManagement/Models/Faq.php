<?php

namespace App\Modules\ContentManagement\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faq extends Model
{
    use SoftDeletes;
    protected $table = 'faqs';

    protected $primaryKey = 'faq_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'question',
        'answer',
        'priority',
        'is_active',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->faq_code = $model->generateCode();
        });
    }

    public function generateCode()
    {
        $prefix = 'FAQ';
        $initialIndex = '00001';
        $faq = self::withTrashed()->latest('id')->first();
        if($faq){
            $codeTobePad = str_replace($prefix,"",$faq->faq_code) +1 ;
            $paddedCode = str_pad($codeTobePad, 5, '0', STR_PAD_LEFT);
            $latestCode = $prefix.$paddedCode;
        }else{
            $latestCode = $prefix.$initialIndex;
        }
        return $latestCode;
    }
}