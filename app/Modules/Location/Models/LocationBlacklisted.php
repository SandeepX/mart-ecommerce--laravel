<?php


namespace App\Modules\Location\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class LocationBlacklisted extends Model
{
    use ModelCodeGenerator;

    protected $table = 'blacklisted_location_hierarchy';
    protected $primaryKey = 'blacklisted_location_hierarchy_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'blacklisted_location_hierarchy_code',
        'location_code',
        'purpose',
        'status',
        'created_by'
    ];

    const PURPOSE = ['store-registration'];

    const RECORDS_PER_PAGE = 10;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->blacklisted_location_hierarchy_code = $model->generateLocationBlacklistedCode();
            $model->created_by = getAuthUserCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });
    }

    public function generateLocationBlacklistedCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'LBC', '1000', false);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_code');
    }

    public function location()
    {
        return $this->belongsTo(LocationHierarchy::class,'location_code','location_code');
    }

}

