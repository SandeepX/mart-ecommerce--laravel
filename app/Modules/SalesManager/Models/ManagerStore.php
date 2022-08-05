<?php


namespace App\Modules\SalesManager\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Application\Traits\SetTimeZone;
use App\Modules\Store\Models\Store;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Modules\User\Models\User;


class ManagerStore extends Model
{
    use  ModelCodeGenerator;

    protected $table = 'manager_store';

    protected $primaryKey = 'manager_store_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [

        'manager_store_code',
        'manager_code',
        'store_code',
        'assigned_by',

    ];

    const ROWS_PER_PAGE = 10;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->manager_store_code = $model->generateCode();
            if(auth()){
                $model->assigned_by = getAuthUserCode();
            }
        });
        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });
    }

    public function generateCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'MS', 1000, false);
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by', 'user_code');
    }

    public function managerCode()
    {
        return $this->belongsTo(User::class, 'manager_code', 'user_code');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_code', 'store_code');
    }

}


