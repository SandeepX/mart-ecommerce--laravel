<?php
use Illuminate\Support\Facades\Route;
Route::group([
    'module'=>'SalesManager',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\SalesManager\Controllers\Web\Admin',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']
], function() {

    //sales manager Route

    Route::get('sales-manager', 'SalesManagerController@index')->name('salesmanager.index');
    Route::get('sales-manager/{managerCode}/show', 'SalesManagerController@show')->name('salesmanager.show');
    Route::post('sales-manager/change/{userCode}/status', 'SalesManagerController@changeStatus')->name('salesmanager.change.status');
    //assign manager a store
    Route::get('sales-manager/assign-store/{managerCode}', 'SalesManagerController@assignStore')->name('salesmanager.assignStore.create');
    Route::post('sales-manager/assign-store/store','AssignStoreController@assignManagerWithStore')->name('salesmanager.assignStore.store');
    Route::delete('sales-manager/unlink-store/{storeManagerCode}','AssignStoreController@unlinkStoreFromManager')->name('salesmanager.assignedStore.destroy');
    Route::get('sales-manager/assign-store/manager/assigned-store/{managerCode}','AssignStoreController@getAllAssignedStoreByManagerCode')->name('salesmanager.assignedStore.show');

//    referred Store
    Route::get('sales-manager/referred-store/{managerCode}','SalesManagerController@getAllReferredStoreByManagerCode')->name('salesmanager.referredStore.show');

    //referred Managers
    Route::get('sales-manager/referred-manager/{managerCode}','SalesManagerController@getAllReferredManagerByManagerCode')->name('salesmanager.referredManager.show');



//change manager password by sandeep
    Route::get('sales-manager/change-password/{managerUserCode}','SalesManagerController@showChangePassword')->name('salesManager.changePassword.show');
    Route::put('sales-manager/change-password/{managerUserCode}','SalesManagerController@updatePassword')->name('salesManager.updateSalesManagerPassword');

    // manager store locations
    Route::get('sales-manager/store-locations','ManagerStoreLocationController@mangerStoreLocation')->name('salesManager.mangerStoreLocation');
});

//smi settings routes
Route::group([
    'module'=>'SalesManager',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\SalesManager\Controllers\Web\Admin',
    'middleware' => ['web','admin.auth','isAdmin',]
], function() {

    Route::resource('manager-smi-setting', 'SMISettingController');

});

Route::group([
    'module'=>'SalesManager',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\SalesManager\Controllers\Web\Admin',
    'middleware' => ['web','admin.auth','isAdmin',]
], function() {

    Route::get('manager-smi', 'ManagerSMIController@index')->name('manager-smi.index');
    Route::get('manager-smi/toggle-status/{msmi_code}', 'ManagerSMIController@toggleStatus')->name('manager-smi.toggle-status');
    Route::get('manager-smi/toggle-edit-Status/{msmi_code}', 'ManagerSMIController@toggleEditStatus')->name('manager-smi.toggle-allow-Edit-status');

    //manager smi detail
    Route::get('manager-smi/detail/{msmi_code}','ManagerSMIController@showDetail')->name('manager-smi.show');
    Route::put('manager-smi/change-status/{msmi_code}','ManagerSMIController@changeStatusOfManagerSMI')->name('manager-smi.changeStatus');
    Route::put('manager-smi/toggle-edit-allow-status/{msmi_code}','ManagerSMIController@toggleEditStatus')->name('manager-smi.toggle-allow-edit-status');

    //manager attendence detail
    Route::get('manager-smi/attendance-detail/{msmi_code}','ManagerAttendanceController@showAttendanceDetail')->name('manager-smi.attendance.show');
    Route::get('manager-smi/attendance/store/{msmi_code}','ManagerAttendanceController@storeAttendaceOfSMI')->name('manager-smi.attendance.store');
    Route::put('manager-smi/past-attedance/update/{attendance_code}','ManagerAttendanceController@updatePastAttendance')->name('manager-smi.past-attendance.update');

});

//social media
Route::group([
    'module'=>'SalesManager',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\SalesManager\Controllers\Web\SocialMedia',
    'middleware' => ['web','admin.auth','isAdmin']
], function() {
    Route::resource('social-media', 'SocialMediaController');
    Route::get('social-media/toggle-enable-status/{SMCode}','SocialMediaController@toggleEnableStatusForSMI')->name('social-media.toggle-status');

});

