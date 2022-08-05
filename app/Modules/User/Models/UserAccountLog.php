<?php

namespace App\Modules\User\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserAccountLog extends Model
{

    use ModelCodeGenerator;
    protected $table = 'user_account_log';
    protected $primaryKey = 'user_account_log_code';
    public $incrementing = false;
    protected $fillable = [
           'account_log_type',
           'account_code',
           'reason',
           'account_status',
           'banned_by',
           'unbanned_by',
           'updated_by',
           'is_unbanned',
           'is_closed'
    ];

    const RECORDS_PER_PAGE=10;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->user_account_log_code = $model->generateUserAccountLogCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function generateUserAccountLogCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'UALC', '1000', false);
    }

    public function user(){
        return $this->belongsTo(User::class,'account_code','user_code');
    }

    public function bannedBy(){
        return $this->belongsTo(User::class,'banned_by','user_code');
    }
    public function unBannedBy(){
        return $this->belongsTo(User::class,'unbanned_by','user_code');
    }

}
