<?php


namespace App\Modules\AlpasalWarehouse\Models\BillMerge;


use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BillMergeDetail extends Model
{
    use ModelCodeGenerator;

    protected $table = 'bill_merge_details';
    protected $primaryKey = 'bill_merge_details_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'bill_merge_master_code',
        'product_code',
        'bill_type',
        'bill_code',
        'status',
    ];


    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->bill_merge_details_code = $model->generateBillMergeDetailCode();
        });
    }
    public function generateBillMergeDetailCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'BMD', '1000', false);
    }
}
