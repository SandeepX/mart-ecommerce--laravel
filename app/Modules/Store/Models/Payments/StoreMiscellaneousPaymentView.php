<?php

namespace App\Modules\Store\Models\Payments;

use App\Modules\Store\Models\Store;
use Illuminate\Database\Eloquent\Model;

class StoreMiscellaneousPaymentView extends Model
{
    protected $table = 'store_miscellaneous_payments_view';
    protected $fillable = [];

    const PAYMENT_FOR = ['initial_registration','load_balance'];
    const VERIFICATION_STATUSES = ['pending', 'verified', 'rejected'];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_code', 'store_code');
    }
}
