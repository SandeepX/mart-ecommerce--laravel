<?php


namespace App\Modules\Vendor\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Application\Traits\SetTimeZone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\User\Models\User;
use App\Modules\Location\Models\LocationHierarchy;
use App\Modules\Vendor\Models\Vendor;
Use App\Modules\Location\Models\Municipality;
Use App\Modules\Location\Models\District;

class VendorTargetMaster extends Model
{
    use SoftDeletes, ModelCodeGenerator, SetTimeZone;

    protected $table = 'vendor_target_master';

    protected $primaryKey = 'vendor_target_master_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [

        'vendor_target_master_code',
        'name',
        'slug',
        'vendor_code',
        'province_code',
        'district_code',
        'municipality_code',
        'start_date',
        'end_date',
        'is_active',
        'status',
        'remark',
        'created_by',
        'updated_by'

    ];

    const STATUS = ['pending', 'processing', 'accepted', 'rejected'];

    const ROWS_PER_PAGE = 10;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->vendor_target_master_code = $model->generateCode();
            $model->created_by = getAuthUserCode();
        });

        static::deleting(function ($model) {
            $model->deleted_by = getAuthUserCode();
            $model->save();
        });
    }

    public function generateCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'VTM', 1000, true);

    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_code');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_code', 'vendor_code');
    }

    public function district()
    {
        return $this->belongsTo(LocationHierarchy::class, 'district_code', 'location_code');
    }

    public function municipality()
    {
        return $this->belongsTo(LocationHierarchy::class, 'municipality_code', 'location_code');
    }

    public function province()
    {
        return $this->belongsTo(LocationHierarchy::class, 'province_code', 'location_code');
    }

}

