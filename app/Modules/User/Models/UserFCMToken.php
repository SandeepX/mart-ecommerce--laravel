<?php

namespace App\Modules\User\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserFCMToken extends Model
{
    use ModelCodeGenerator;
    protected $table = 'user_fcm_tokens';
    protected $primaryKey = 'user_fcm_token_code';
    public $incrementing = false;
    protected $fillable = [
        'user_fcm_token_code',
        'user_code',
        'fcm_token'
    ];


    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->user_fcm_token_code = $model->generateUserDocCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function generateUserDocCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'UFT', '1000', false);
    }

}
