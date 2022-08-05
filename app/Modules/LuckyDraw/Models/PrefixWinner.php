<?php

namespace App\Modules\LuckyDraw\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Store\Models\Store;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class PrefixWinner extends Model
{
    use SoftDeletes,ModelCodeGenerator;
    protected $table = 'prefix_winners';

    protected $primaryKey = 'prefix_winner_code';
    public $incrementing = false;
    protected $keyType = 'string';


    protected $fillable = [
        'store_luckydraw_code',
        'store_code',
        'remarks',
        'sort_order',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->prefix_winner_code = $model->generatePrefixWinnerCode();
            $model->created_by = getAuthUserCode();
            $model->updated_by = getAuthUserCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
            $model->updated_by = getAuthUserCode();
        });

        static::deleting(function ($model) {
            $model->deleted_at = Carbon::now();
            $model->deleted_by = getAuthUserCode();
        });

    }

    public function generatePrefixWinnerCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'PWC', '1000', true);
    }

    public function storeLuckydraw(){
        return $this->belongsTo(StoreLuckydraw::class, 'store_luckydraw_code');
    }

    public function store(){
        return $this->belongsTo(Store::class, 'store_code');
    }
}
