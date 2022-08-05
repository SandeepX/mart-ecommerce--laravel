<?php



Route::group([
    'module'=>'Lead',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\Lead\Controllers\Web\Admin',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']
], function() {

    Route::resource('leads', 'LeadController');
    
    Route::get('leads/{lead}/documents', 'LeadDocumentController@create')->name('leads.documents.create');
    Route::post('leads/{lead}/documents', 'LeadDocumentController@store')->name('leads.documents.store');
    Route::delete('leads/{lead}/documents/{documentID}', 'LeadDocumentController@destroy')->name('leads.documents.destroy');
});