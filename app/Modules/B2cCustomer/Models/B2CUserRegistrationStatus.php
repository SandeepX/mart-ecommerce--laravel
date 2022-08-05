<?php


namespace App\Modules\B2cCustomer\Models;


use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class B2CUserRegistrationStatus extends Model
{
    use ModelCodeGenerator;

    protected $table = 'b2c_registration_status';
    protected $primaryKey = 'b2c_registration_status_code';
    public $incrementing = false;

    protected $fillable = [
        'b2c_registration_status_code',
        'user_code',
        'status',
        'remarks',
    ];


    const STATUS = ['pending', 'processing', 'approved', 'rejected'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->b2c_registration_status_code = $model->generateUserRegistrationStatusCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function generateUserRegistrationStatusCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'BTC', '1000', false);
    }

    public function user(){
        return $this->belongsTo(User::class,'user_code','user_code');
    }


}

