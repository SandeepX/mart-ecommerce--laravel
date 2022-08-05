<?php

namespace App\Modules\ContentManagement\Models;


use Illuminate\Database\Eloquent\Model;

class SitePage extends Model
{
    protected $table = 'site_pages';

    protected $primaryKey = 'site_page_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'content',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->site_page_code = $model->generateCode();
        });
    }

    public function generateCode()
    {
        $prefix = 'SP';
        $initialIndex = '00001';
        $sitePage = self::latest('id')->first();
        if($sitePage){
            $codeTobePad = str_replace($prefix,"",$sitePage->site_page_code) +1 ;
            $paddedCode = str_pad($codeTobePad, 5, '0', STR_PAD_LEFT);
            $latestCode = $prefix.$paddedCode;
        }else{
            $latestCode = $prefix.$initialIndex;
        }
        return $latestCode;
    }
}