<?php

namespace App\Modules\SystemSetting\Models;

use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;

class EnvMailSetting extends Model
{
    protected $table = 'env_mail_settings';
    protected $fillable = [
        'mail_mailer',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
        'updated_by',
    ];

    const ENV_MAIL_KEYS = [
        //'env_key' => 'mail.php equivalent'
        'MAIL_MAILER' => 'mail.default',
        'MAIL_HOST' => 'mail.mailers.smtp.host',
        'MAIL_PORT' => 'mail.mailers.smtp.port',
        'MAIL_USERNAME' => 'mail.mailers.smtp.username',
        'MAIL_PASSWORD' => 'mail.mailers.smtp.password',
        'MAIL_ENCRYPTION' => 'mail.mailers.smtp.encryption',
        'MAIL_FROM_ADDRESS' => 'mail.from.address',
        'MAIL_FROM_NAME' => 'mail.from.name',
    ];

    const MAIL_DRIVERS=[
        'smtp','ses','mailgun'
    ];

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'user_code');
    }
}
