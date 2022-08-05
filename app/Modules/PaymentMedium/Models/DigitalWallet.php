<?php

namespace App\Modules\PaymentMedium\Models;

use App\Modules\Application\Traits\IsActiveScope;
use Illuminate\Database\Eloquent\Model;

class DigitalWallet extends Model
{

    use IsActiveScope;

    protected $table = 'digital_wallets';

    protected $primaryKey = 'wallet_code';
    public $incrementing = false;
    protected $keyType = 'string';

    public function getConnectIpsCode(){
        return 'DW06';
    }
}
