<?php

namespace App\Modules\User\Models;


use App\Modules\B2cCustomer\Models\B2CUserRegistrationStatus;
use App\Modules\EnquiryMessage\Models\EnquiryMessage;
use App\Modules\Location\Models\LocationHierarchy;
use App\Modules\Location\Traits\LocationHelper;
use App\Modules\Location\Models\Ward;
use App\Modules\MiniMart\Models\StoreType;


use App\Modules\OTP\Models\OTP;
use App\Modules\SalesManager\Models\Manager;
use App\Modules\SalesManager\Models\SalesManagerRegistrationStatus;
use App\Modules\Store\Models\Store;


use App\Modules\Cart\Models\Cart;
use App\Modules\Types\Models\UserType;
use App\Modules\User\Notifications\VerifyB2CUserEmail;
use App\Modules\User\Notifications\VerifySalesManagerUserEmail;
use App\Modules\User\Notifications\VerifyUserEmail;
use App\Modules\Vendor\Models\Vendor;

use App\Notifications\ResetPasswordMailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;
use League\Flysystem\Exception;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable, SoftDeletes, HasRoles, LocationHelper;

    protected $table = 'users';

    protected $primaryKey = 'user_code';
    public $incrementing = false;
    protected $keyType = 'string';


    protected $fillable = [
        'id',
        'user_code',
        'user_type_code',
        'name',
        'login_email',
        'login_phone',
        'password',
        'avatar',
        'is_phone_verified',
        'is_email_verified_at',
        'email_verified_at',
        'phone_verified_at',
        'is_first_login',
        'first_login_at',
        'last_login_ip',
        'last_login_at',
//        'country_code',
//        'province_code',
//        'district_code',
//        'municipality_code',
//        'ward_code',
//        'street_name',
//        'citizenship_number_eng',
//        'citizenship_number_nep',
//        'has_two_wheeler_license',
//        'has_four_wheeler_license',
        'gender',
  //      'temporary_ward',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
        'remarks'
//        'referral_code'
    ];


    protected $hidden = [
        'password',
        'remember_token',
        'user_code',
        'login_phone',
        'login_email',
        'created_by',
        'updated_by',
        'deleted_by',
        'remarks'
    ];

    const AVATAR_UPLOAD_PATH = 'uploads/user/avatar/';
    const RECORDS_PER_PAGE = 10;

    const GENDER = ['male','female','others'];

//    const MANAGER_DOC_TYPES = [
//        'citizenship_front',
//        'citizenship_back',
//        'pan_card_front',
//        'pan_card_back',
//        'slc_see_certificate',
//        'plus_2_certificate',
//        'bachelors_certificate',
//        'masters_certificate'
//    ];


    /**
     * The attributes that should -be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function generateUserCode()
    {
        $userPrefix = 'U';
        $initialIndex = '00000001';
        $user = self::withTrashed()->latest('id')->first();
        if ($user) {
            $codeTobePad = str_replace($userPrefix, "", $user->user_code) + 1;
            $paddedCode = str_pad($codeTobePad, 8, '0', STR_PAD_LEFT);
            $latestuserCode = $userPrefix . $paddedCode;
        } else {
            $latestuserCode = $userPrefix . $initialIndex;
        }
        return $latestuserCode;
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->login_email = time() . '::' . $model->login_email;
            $model->login_phone = time() . '::' . $model->login_phone;
        });
    }

    public function isStoreUser()
    {
        return $this->userType->slug == "store" ? true : false;
    }

    public function isVendorUser()
    {
        return $this->userType->slug == "vendor" ? true : false;
    }

    public function isSuperAdmin()
    {
        return $this->userType->slug == "super-admin" ? true : false;
    }

    public function isAdminUser()
    {
        return $this->userType->slug == "super-admin" || $this->userType->slug == "admin" ? true : false;
    }

    public function isGeneralAdminUser()
    {
        return $this->userType->slug == "admin" ? true : false;
    }

    public function isWarehouseAdminOrUser()
    {
        return $this->isWarehouseAdmin() || $this->isWarehouseUser() ? true : false;
    }

    public function isWarehouseAdmin()
    {
        return $this->userType->slug == "warehouse-admin" ? true : false;
    }

    public function isWarehouseUser()
    {
        return $this->userType->slug == "warehouse-user" ? true : false;
    }

    public function isSupportAdmin()
    {
        return $this->userType->slug == "support-admin" ? true : false;
    }


    /**
     * Find the user instance for the given username.
     *
     * @param string $username
     * @return \App\User
     */
    public function findForPassport($username)
    {
        return $this->where('login_email', $username)->first();
    }


    /**
     * Validate the password of the user for the Passport password grant.
     *
     * @param string $password
     * @return bool
     */
    public function validateForPassportPasswordGrant($password)
    {
        return Hash::check($password, $this->password);
    }

    public function getEmailForPasswordReset()
    {
        return $this->login_email;
    }

    public function routeNotificationFor($driver, $notification = null)
    {
        if (method_exists($this, $method = 'routeNotificationFor' . Str::studly($driver))) {
            return $this->{$method}($notification);
        }

        switch ($driver) {
            case 'database':
                return $this->notifications();
            case 'mail':
                return $this->login_email;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $userType = $this->userType->slug;
        $resetUrl = '/';

        if ($this->isAdminUser()) {
            $resetUrl = route('admin.reset.password', ['token' => $token, 'login_email' => $this->login_email]);
        } elseif ($this->isWarehouseAdminOrUser()) {
            $resetUrl = route('warehouse.reset.password', ['token' => $token, 'login_email' => $this->login_email]);
        } elseif ($this->isSupportAdmin()) {
            $resetUrl = route('support-admin.reset.password', ['token' => $token, 'login_email' => $this->login_email]);
        }
         else {
            $resetUrl = config('site_urls.ecommerce_site') . "/reset/password/" . $token . '?login_email=' . $this->login_email;
        }

        $this->notify(new ResetPasswordMailNotification($resetUrl));
    }

    public function vendor()
    {
        return $this->hasOne(Vendor::class, 'user_code')->withDefault();
    }

    public function store()
    {
        return $this->hasOne(Store::class, 'user_code')->withDefault();
    }
    public function manager()
    {
        return $this->hasOne(Manager::class, 'user_code')->withDefault();
    }
    public function userType()
    {
        return $this->belongsTo(UserType::class, 'user_type_code')->withDefault();
    }

    public function createBY()
    {
        return $this->belongsTo(StoreType::class, 'user_code');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'user_code');
    }

    public function warehouseUser()
    {
        return $this->hasOne(WarehouseUser::class, 'user_code', 'user_code');
    }

    public function sentEnquiryMessages()
    {
        return $this->hasMany(EnquiryMessage::class, 'sender_code', 'user_code');
    }

    public function receivedEnquiryMessages()
    {
        return $this->hasMany(EnquiryMessage::class, 'receiver_code', 'user_code');
    }

    public function limitedUserNotifications($limit)
    {
        return $this->notifications()->limit($limit)->get();
    }

    public static function getSuperAdminUserCode()
    {
        $superAdminUserType = UserType::where('slug', 'super-admin')->first();
        $superAdmin = self::where('user_type_code', $superAdminUserType->user_type_code)->first();
        return $superAdmin->user_code;
    }

    public function sendEmailVerificationNotification()
    {
        if ($this->isSalesManagerUser()) {
            $this->notify(new VerifySalesManagerUserEmail());
        } elseif($this->isB2CUser()) {
            $this->notify(new VerifyB2CUserEmail());
        }else{
            $this->notify(new VerifyUserEmail());
        }
    }

    public function userAccountLogs()
    {
        return $this->hasMany(UserAccountLog::class, 'account_code', 'user_code');
    }

    public function latestUserAccountLog()
    {
        return $this->userAccountLogs()->latest('id');
    }

    public function isBanned()
    {
        $latestAccountLog = $this->latestUserAccountLog->first();
        if ($latestAccountLog) {
            if ($latestAccountLog->is_closed == 0
                &&
                $latestAccountLog->account_status == 'permanently_banned') {
                return true;
            }
        }

        return false;
    }

    public function isSuspended()
    {
        $latestAccountLog = $this->latestUserAccountLog->first();
        if ($latestAccountLog) {
            if ($latestAccountLog->is_closed == 0
                &&
                $latestAccountLog->account_status == 'suspend') {
                return true;
            }
        }
        return false;
    }

    public function isActive()
    {
        if ($this->is_active == 1) {
            return true;
        }
        return false;
    }

    public function isPhoneVerified()
    {
        if($this->is_phone_verified == 1){
            return true;
        }
        return false;
    }

    public function userCreatedBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_code');
    }

    public function isSalesManagerUser()
    {
        return $this->userType->slug == "sales-manager" ? true : false;
    }

    public function salesManagerRegistrationStatus()
    {
        return $this->hasOne(SalesManagerRegistrationStatus::class, 'user_code', 'user_code')->withDefault();
    }

    public function isB2CUser()
    {
        return $this->userType->slug == "b2c-customer" ? true : false;
    }

    public function userB2CRegistrationStatus()
    {
        return $this->hasOne(B2CUserRegistrationStatus::class, 'user_code', 'user_code')->withDefault();
    }

    public function userDocs()
    {
        return $this->hasMany(UserDoc::class, 'user_code', 'user_code');
    }

    public function permanentLocation()
    {
        return $this->belongsTo(LocationHierarchy::class, 'ward_code', 'location_code');
    }

    public function temporaryLocation()
    {
        return $this->belongsTo(LocationHierarchy::class, 'temporary_ward', 'location_code');
    }

    public function ward()
    {
        return $this->belongsTo(LocationHierarchy::class, 'ward_code', 'location_code');
    }


    public function wallet()
    {
        return $this->morphOne('App\Modules\Wallet\Models\Wallet', 'walletable', 'wallet_holder_type', 'wallet_holder_code');
    }

    public function onlinePayment()
    {
        return $this->morphMany('App\Modules\PaymentGateway\Models\OnlinePaymentMaster', 'onlinePaymentable', 'payment_initiator','initiator_code');
    }

    public function otp()
    {
        return $this->hasMany(OTP::class, 'user_code', 'user_code');
    }

    public function isAccountVerified(){
        if(!is_null($this->phone_verified_at)  || !is_null($this->email_verified_at)){
            return true;
        }
        return false;
    }


}
