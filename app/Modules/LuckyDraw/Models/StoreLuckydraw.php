<?php

namespace App\Modules\LuckyDraw\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class StoreLuckydraw extends Model
{
    use SoftDeletes,ModelCodeGenerator;
    protected $table = 'store_luckydraws';

    protected $primaryKey = 'store_luckydraw_code';
    public $incrementing = false;
    protected $keyType = 'string';

    const IMAGE_PATH='uploads/prizes/images/';

    protected $fillable = [
        'luckydraw_name',
        'slug',
        'type',
        'prize',
        'eligibility_sales_amount',
        'days',
        'terms',
        'youtube_link',
        'opening_time',
        'pickup_time',
        'remarks',
        'status',
        'image',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->store_luckydraw_code = $model->generateStoreLuckyDrawCode();
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

    public function generateStoreLuckyDrawCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'SLDC', '1000', true);
    }

    public function storeLuckydrawWinners(){
        return $this->hasMany(StoreLuckydrawWinner::class, 'store_luckydraw_code','store_luckydraw_code')
            ->orderBy('winner_eligibility','desc');
    }

    public function perfectLuckydrawWinner(){
      return $this->storeLuckydrawWinners()->where('winner_eligibility',1)->first();
    }

    public function notEligibleWinners(){
        return $this->storeLuckydrawWinners()->where('winner_eligibility',0)->get();
    }

    public function prefixWinners(){
        return $this->hasMany(PrefixWinner::class, 'store_luckydraw_code','store_luckydraw_code');
    }

    public function getOpeningTime($dateFormat='Y-m-d H:i:s')
    {
        return date($dateFormat,strtotime($this->opening_time));
    }

    public function eligibleWinners()
    {
        return $this->hasMany(StoreLuckydrawWinner::class,'store_luckydraw_code','store_luckydraw_code')
            ->where('winner_eligibility',1);
    }

    public function orderedPrefixWinners()
    {
        return $this->hasMany(PrefixWinner::class,'store_luckydraw_code','store_luckydraw_code')
             ->orderBy('sort_order','ASC');
    }
}
