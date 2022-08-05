<?php


namespace App\Modules\Store\Models\PreOrder;

use App\User;
use Illuminate\Database\Eloquent\Model;

class StorePreorderEarlyCancellation extends Model
{

    protected $table = 'store_preorder_early_cancellation';
    protected $primaryKey = 'id';

    protected $fillable = [
        'store_preorder_code',
        'early_cancelled_date',
        'early_cancelled_remarks',
        'early_cancelled_by'
    ];

    public function storePreOrder()
    {
        return $this->belongsTo(StorePreOrder::class, 'store_preorder_code', 'store_preorder_code');
    }

    public function earlyCancelledBy()
    {
        return $this->belongsTo(User::class, 'early_cancelled_by', 'user_code');
    }


}

