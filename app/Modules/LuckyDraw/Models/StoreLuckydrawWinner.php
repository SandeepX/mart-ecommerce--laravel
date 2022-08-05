<?php

namespace App\Modules\LuckyDraw\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Store\Models\Store;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class StoreLuckydrawWinner extends Model
{
    use SoftDeletes,ModelCodeGenerator;
    protected $table = 'store_luckydraw_winners';

    protected $primaryKey = 'store_luckydraw_winner_code';
    public $incrementing = false;
    protected $keyType = 'string';


    protected $fillable = [
        'store_luckydraw_code',
        'store_code',
        'winner_eligibility',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->store_luckydraw_winner_code = $model->generateStoreLuckydrawWinnerCode();
            $model->created_by = "U00000001";
            $model->updated_by = "U00000001";
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
            $model->updated_by = "U00000001";
        });

        static::deleting(function ($model) {
            $model->deleted_at = Carbon::now();
            $model->deleted_by = "U00000001";
        });

    }

    public function generateStoreLuckydrawWinnerCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'SLDWC', '1000', true);
    }

    public function storeLuckydraw(){
        return $this->belongsTo(StoreLuckydraw::class, 'store_luckydraw_code');
    }

    public function store(){
        return $this->belongsTo(Store::class, 'store_code');
    }

}
