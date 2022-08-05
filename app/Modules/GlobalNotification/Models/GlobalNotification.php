<?php


namespace App\Modules\GlobalNotification\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class GlobalNotification extends Model
{
    use ModelCodeGenerator;

    protected $table = 'global_notification';
    protected $primaryKey = 'global_notification_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'message',
        'link',
        'file',
        'created_by',
        'created_for',
        'start_date',
        'end_date',
        'is_active',
    ];

    const created_for = ['store','vendor','warehouse','all'];

    const DOCUMENT_PATH = 'uploads/globalNotification/files/';

    const RECORDS_PER_PAGE=10;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->global_notification_code = $model->generateGobalNotificationCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function generateGobalNotificationCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'GN', '1000', false);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','user_code');
    }

}
