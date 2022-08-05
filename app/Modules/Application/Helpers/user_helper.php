<?php
if (!function_exists('getAuthUserCode')) {
    function getAuthUserCode()
    {
        return auth()->user()->user_code;
    }
}

if (!function_exists('getAuthGuardUserCode')) {
    function getAuthGuardUserCode()
    {
        return auth('api')->user()->user_code;
    }
}

if (!function_exists('getAuthGuardStoreCode')) {
    function getAuthGuardStoreCode()
    {
        return auth('api')->user()->store->store_code;
    }
}

if (!function_exists('getAuthVendorCode')) {
    function getAuthVendorCode()
    {
        return auth()->user()->vendor->vendor_code;
    }
}

if (!function_exists('getAuthStoreCode')) {
    function getAuthStoreCode()
    {
        return auth()->user()->store->store_code;
    }
}

if (!function_exists('getAuthManagerCode')) {
    function getAuthManagerCode()
    {
        return auth()->user()->manager->manager_code;
    }
}


if (!function_exists('getAuthWarehouseCode')) {
    function getAuthWarehouseCode()
    {
        return auth()->user()->warehouseUser->warehouse->warehouse_code;
    }
}

if (!function_exists('getAuthWarehouse')) {
    function getAuthWarehouse()
    {
        return auth()->user()->warehouseUser->warehouse;
    }
}

if (!function_exists('userTypes')) {
    function userTypes()
    {
        return [
            'super-admin' => 'super-admin',
            'admin' => 'admin',
            'vendor' => 'vendor',
            'store' => 'store',
            'warehouse-admin' => 'warehouse',
            'warehouse-user' => 'warehouse',
            'b2c-customer' => 'normal-user',
            'sales-manager' => 'manager'
        ];
    }
}

if (!function_exists('getAuthParentUserType')) {
    function getAuthParentUserType()
    {
        $userTypes= userTypes();
        // dd(auth('api')->user()->userType->slug);
        return $userTypes[auth('api')->user()->userType->slug];
    }
}


if (!function_exists('getAuthStore')) {
    function getAuthStore()
    {
        return auth()->user()->store;
    }
}

if(!function_exists('getAuthSalesManagerRegistrationStatus')){
    function getAuthSalesManagerRegistrationStatus(){
      return auth()->user()->salesManagerRegistrationStatus;
    }
}

if(!function_exists('getAuthB2CRegistrationStatus')){
    function getAuthB2CRegistrationStatus(){
        return auth()->user()->userB2CRegistrationStatus;
    }
}

if (!function_exists('getSuperAdminUserCode')) {
    function getSuperAdminUserCode()
    {
        $userDetail =  \App\Modules\User\Models\User::where('user_type_code','UT001')->first();
        return $userDetail['user_code'];
    }
}

