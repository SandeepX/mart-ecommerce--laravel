<?php

namespace App\Modules\SystemSetting\Models;

use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;

class EnvUrlSetting extends Model
{
    protected $table = 'env_url_settings';
    protected $fillable = [
        'ecommerce_site_url',
        'updated_by',
    ];

    const ENV_URL_KEYS = [
        //'env_key' => 'site_urls.php equivalent'
        'ECOMMERCE_SITE_URL' => 'site_urls.ecommerce_site',
    ];

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'user_code');
    }
}
