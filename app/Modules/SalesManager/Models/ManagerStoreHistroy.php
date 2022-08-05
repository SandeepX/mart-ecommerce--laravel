<?php


namespace App\Modules\SalesManager\Models;



use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Application\Traits\SetTimeZone;
use App\Modules\Store\Models\Store;
use Illuminate\Database\Eloquent\Model;
use App\Modules\User\Models\User;


class ManagerStoreHistroy extends Model
{
    use  ModelCodeGenerator;

    protected $table = 'manager_store_history';

    protected $primaryKey = 'manager_store_history_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [

        'manager_store_history_code',
        'manager_code',
        'store_code',
        'assigned_date',
        'assigned_by',
        'removed_date'

    ];

    const ROWS_PER_PAGE = 10;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->manager_store_history_code = $model->generateCode();
            $model->assigned_by = getAuthUserCode();
        });
    }

    public function generateCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'MSH', 1000, false);
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



