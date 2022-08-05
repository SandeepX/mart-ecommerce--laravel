<?php

namespace App\Modules\EnquiryMessage\Models;

use App\Modules\Location\Models\LocationHierarchy;
use App\Modules\Store\Models\Store;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnquiryMessage extends Model
{
    use SoftDeletes;
    protected $table = 'store_message';
    protected $primaryKey = 'store_message_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'parent_id',
        'department',
        'store_message_code',
        'sender_code',
        'receiver_code',
        'store_message_file',
        'subject',
        'message',
        'sender_ip',
    ];


    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            $model->store_message_code = $model->generateEnquiryMessageCode();
//            $model->receiver_code = "UT001";
        });


    }


    public function generateEnquiryMessageCode()
    {
        $enquiryMessagePrefix = 'EMC';
        $initialIndex = '1000';
        $enquiryMessage = self::withTrashed()->latest('id')->first();
        if($enquiryMessage){
            $codeTobePad = (int) (str_replace($enquiryMessagePrefix,"",$enquiryMessage->store_message_code) +1 );
            //$paddedCode = str_pad($codeTobePad, 5, '0', STR_PAD_LEFT);
            $latestEnquiryMessageCode = $enquiryMessagePrefix.$codeTobePad;
        }else{
            $latestEnquiryMessageCode = $enquiryMessagePrefix.$initialIndex;
        }
        return $latestEnquiryMessageCode;
    }
 public function senderUser()
 {
     return $this->belongsTo(User::class,'sender_code','user_code');
 }
    public function receiverUser()
    {
        return $this->belongsTo(User::class,'receiver_code','user_code');
    }
}
