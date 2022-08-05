<?php

namespace App\Modules\Newsletter\Models;

use App\Modules\Application\Traits\IsActiveScope;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use IsActiveScope;

    protected $table = 'subscribers';
    protected $primaryKey = 'subscriber_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['subscriber_code', 'email', 'token', 'is_active'];

    const RECORDS_PER_PAGE=10;
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->subscriber_code = $model->generateSubscriberCode();
        });
    }

    public static function generateSubscriberCode()
    {
        $subscriberPrefix = 'S';
        $initialIndex = '00001';
        $subscriber = self::latest('id')->first();
        if($subscriber){
            $codeTobePad = str_replace($subscriberPrefix,"",$subscriber->subscriber_code) +1 ;
            $paddedCode = str_pad($codeTobePad, 5, '0', STR_PAD_LEFT);
            $latestBrandCode = $subscriberPrefix.$paddedCode;
        }else{
            $latestBrandCode = $subscriberPrefix.$initialIndex;
        }
        return $latestBrandCode;
    }

}
