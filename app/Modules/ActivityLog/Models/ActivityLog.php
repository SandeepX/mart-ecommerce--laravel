<?php

namespace App\Modules\ActivityLog\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_log';
    protected $fillable = [
        'subject', 'url', 'method', 'ip', 'agent','data', 'user_code'
    ];
}
