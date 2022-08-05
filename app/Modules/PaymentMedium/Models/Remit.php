<?php

namespace App\Modules\PaymentMedium\Models;

use App\Modules\Application\Traits\IsActiveScope;
use Illuminate\Database\Eloquent\Model;

class Remit extends Model
{
    use IsActiveScope;
    protected $table = 'remits';
    protected $primaryKey = 'remit_code';
    public $incrementing = false;
    protected $keyType = 'string';
}
