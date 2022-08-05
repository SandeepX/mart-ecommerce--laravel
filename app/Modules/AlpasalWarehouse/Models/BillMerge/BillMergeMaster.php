<?php


namespace App\Modules\AlpasalWarehouse\Models\BillMerge;


use App\Modules\AlpasalWarehouse\Models\Warehouse;
use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Store\Models\Store;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BillMergeMaster extends Model
{
    use ModelCodeGenerator;

    protected $table = 'bill_merge_master';
    protected $primaryKey = 'bill_merge_master_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'warehouse_code',
        'store_code',
        'status',
        'remarks',
        'created_by',
    ];


    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->bill_merge_master_code = $model->generateBillMergeMasterCode();
            $model->created_by = $authUserCode;
        });
    }
    public function generateBillMergeMasterCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'BMM', '1000', false);
    }

    public function store()
    {
        return $this->belongsTo(Store::class,'store_code','store_code');
    }

    public function warehouse(){
        return $this->belongsTo(Warehouse::class,'warehouse_code','warehouse_code');
    }

    public function billMergeDetails(){
        return $this->hasMany(BillMergeDetail::class,'bill_merge_master_code','bill_merge_master_code');
    }
}
