<?php


namespace App\Modules\ProductRatingReview\Models;


use App\Modules\Application\Traits\IsActiveScope;
use App\Modules\Store\Models\Store;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreProductReviewReply extends Model
{
    use SoftDeletes ,IsActiveScope;

    protected $table = 'store_product_review_replies';
    protected $primaryKey = 'reply_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'review_code',
        'user_code',
        'reply_message',
        'is_active',
        'remarks',
        'updated_by'
    ];

    public function generateCode()
    {
        $prefix = 'SPRRV';
        $initialIndex = '1000';
        $review = self::withTrashed()->latest('id')->first();
        if($review){
            $codeTobePad = (int) (str_replace($prefix,"",$review->reply_code) +1 );
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
            $model->reply_code = $model->generateCode();
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

    //review reply done by user
    public function user(){
        return $this->belongsTo(User::class,'user_code','user_code');
    }

    public function storeProductReview(){
        return $this->belongsTo(StoreProductReview::class,'review_code','review_code');
    }
}
