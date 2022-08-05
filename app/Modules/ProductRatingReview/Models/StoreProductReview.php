<?php


namespace App\Modules\ProductRatingReview\Models;


use App\Modules\Application\Traits\IsActiveScope;
use App\Modules\Store\Models\Store;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreProductReview extends Model
{
    use SoftDeletes ,IsActiveScope;

    protected $table = 'store_product_reviews';
    protected $primaryKey = 'review_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'warehouse_code',
        'product_code',
        'store_code',
        'user_code',
        'review_message',
        'is_active',
        'remarks',
        'updated_by'
    ];

    public function generateCode()
    {
        $prefix = 'SPRV';
        $initialIndex = '1000';
        $review = self::withTrashed()->latest('id')->first();
        if($review){
            $codeTobePad = (int) (str_replace($prefix,"",$review->review_code) +1 );
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
            $model->review_code = $model->generateCode();
            $model->user_code = $authUserCode;
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

    //review done by store
    public function store(){
        return $this->belongsTo(Store::class,'store_code','store_code');
    }

    //review done by user
    public function user(){
        return $this->belongsTo(User::class,'user_code','user_code');
    }

    public function storeProductReviewReplies(){
        return $this->hasMany(StoreProductReviewReply::class,'review_code','review_code');
    }
}
