<?php


namespace App\Modules\ProductRatingReview\Models;


use App\Modules\Store\Models\Store;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;

class StoreProductRating extends Model
{
    protected $table = 'store_product_ratings';
    protected $primaryKey = 'rating_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'warehouse_code',
        'product_code',
        'store_code',
        'user_code',
        'rating',
    ];

    public function generateCode()
    {
        $prefix = 'SPR';
        $initialIndex = '1000';
        $rating = self::latest('id')->first();
        if($rating){
            $codeTobePad = (int) (str_replace($prefix,"",$rating->rating_code) +1 );
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
            $model->rating_code   = $model->generateCode();
        });
    }

    //rating done by store
    public function store(){
        return $this->belongsTo(Store::class,'store_code','store_code');
    }

    //rating done by user
    public function user(){
        return $this->belongsTo(User::class,'user_code','user_code');
    }
}
