<?php

namespace App\Modules\SystemSetting\Models;

use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;

class EnvPassportSetting extends Model
{
    protected $table = 'env_passport_settings';
    protected $fillable = [
        'passport_login_endpoint',
        'passport_client_id',
        'passport_client_secret',
        'updated_by',
    ];

    const ENV_PASSPORT_KEYS = [
        //'env_key' => 'services.php equivalent'
        'PASSPORT_LOGIN_ENDPOINT' => 'services.passport.login_endpoint',
        'PASSPORT_CLIENT_ID' => 'services.passport.client_id',
        'PASSPORT_CLIENT_SECRET' => 'services.passport.client_secret',
    ];

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'user_code');
    }
}
