<?php

namespace App\Modules\Vendor\Models;

use App\Modules\Application\Traits\IsActiveScope;
use App\Modules\Location\Models\LocationHierarchy;
use App\Modules\Location\Traits\LocationHelper;
use App\Modules\Product\Models\ProductMaster;
use App\Modules\Types\Models\CompanyType;
use App\Modules\Types\Models\RegistrationType;
use App\Modules\Types\Models\VendorType;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use SoftDeletes,LocationHelper,IsActiveScope;
    protected $table = 'vendors_detail';

    protected $primaryKey = 'vendor_code';
    public $incrementing = false;
    protected $keyType = 'string';

    const IMAGE_PATH='uploads/vendors/logo/';
    const VENDOR_PER_PAGE =10;

    protected $fillable = [
        'vendor_code',
        'vendor_name',
        'slug',
        'vendor_type_code',
        'registration_type_code',
        'company_type_code',
        'company_size',
        'vendor_location_code',
        'vendor_landmark',
        'landmark_latitude',
        'landmark_longitude',
        'vendor_owner',
        'pan',
        'vat',
        'contact_person',
        'contact_landline',
        'contact_mobile',
        'contact_email',
        'contact_fax',
        'phone_verified_at',
        'email_verified_at',
        'user_code',
        'vendor_logo',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public static function generateVendorCode()
    {
        $vendorPrefix = 'VN';
        $initialIndex = '1000';
        $vendor = self::withTrashed()->latest('created_at')->first();
        if($vendor){
            $codeTobePad = (int) (str_replace($vendorPrefix,"",$vendor->vendor_code) +1 );
           // $paddedCode = str_pad($codeTobePad, 7, '0', STR_PAD_LEFT);
            $latestVendorCode = $vendorPrefix.$codeTobePad;
        }else{
            $latestVendorCode = $vendorPrefix.$initialIndex;
        }
        return $latestVendorCode;
    }

    public function vendorType(){
        return $this->belongsTo(VendorType::class, 'vendor_type_code', 'vendor_type_code');
    }

    public function registrationType(){
        return $this->belongsTo(RegistrationType::class, 'registration_type_code', 'registration_type_code');
    }

    public function companyType(){
        return $this->belongsTo(CompanyType::class, 'company_type_code', 'company_type_code');
    }

    public function banners(){
        return $this->hasMany(VendorBanner::class, 'vendor_code');
    }

    public function documents(){
        return $this->hasMany(VendorDocument::class, 'vendor_code');
    }

    public function vendorWarehouses(){
        return $this->hasMany(VendorWareHouse::class, 'vendor_code');
    }

    public function products(){
        return $this->hasMany(ProductMaster::class, 'vendor_code');
    }

    public function receivedOrders(){
        return $this->hasMany(OrderReceivedByVendor::class, 'order_received_by');
    }

    public function location(){
        return $this->belongsTo(LocationHierarchy::class, 'vendor_location_code');
    }

    public function user(){

        return $this->belongsTo(User::class,'user_code');
    }
    public function getLogoUploadPath(){

        return self::IMAGE_PATH;
    }

    public function vendorUserTypeCode(){
        return $this->user->userType->user_type_code;
    }

    public function isAccountVerified(){
        if($this->phone_verified_at  || $this->email_verified_at){
            return true;
        }
        return false;
    }
}
