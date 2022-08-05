<?php


Route::group(['prefix' => 'api'], function () {
    Route::group([
        'module' => 'Category',
        'prefix' => 'admin',
        'namespace' => 'App\Modules\Category\Controllers\Api\Admin',
    ], function () {
        Route::apiResource('categories', 'CategoryController');
    });

    Route::group([
        'module' => 'Category',
        'namespace' => 'App\Modules\Category\Controllers\Api\Frontend',
        'middleware' => ['isMaintenanceModeOn']
    ], function () {

        Route::get('categories/{category}/brands', 'CategoryBrandController@getBrandsOfRootCategory')->name('get.category.brands');
        Route::get('category-tree', 'CategoryController@getCategoryTree');
        Route::get('category-reverse-tree/{category}', 'CategoryController@getCategoryReverseTree');
        Route::get('category-family/{category}', 'CategoryController@getCategoryFamily');
        Route::get('root-categories', 'CategoryController@getRootCategories');
        Route::get('categories/{category}/lower-categories', 'CategoryController@getLowerCategories');
        Route::get('categories/slug/{category}/lower-categories', 'CategoryController@getLowerCategoriesByCatSlug');
        Route::get('categories/details/{category}', 'CategoryController@getCategoryInfo');
        Route::get('categories/{category}/upper-category-with-siblings', 'CategoryController@getDaddyWithHisSiblingCategories');
        Route::get('categories/slug/{category}/upper-category-with-siblings', 'CategoryController@getDaddyWithHisSiblingCategoriesBySlug');
        Route::get('search/category-paths', 'CategoryController@searchCategoryPath');
    });

});


Route::group([
    'module' => 'Category',
    'prefix' => 'admin',
    'as' => 'admin.',
    'namespace' => 'App\Modules\Category\Controllers\Web\Admin',
    'middleware' => ['web', 'admin.auth', 'isAdmin', 'ipAccess']
], function () {
    Route::get('categories/brands', 'CategoryBrandController@getCategoryBrands')->name('categories.brands.index');
    Route::get('categories/{category}/brands', 'CategoryBrandController@showCategoryBrands')->name('categories.brands.show');
    Route::get('category/create-brands', 'CategoryBrandController@brandCategoryPage')->name('categories.brands.create');
    Route::post('category/store-brands', 'CategoryBrandController@syncCategoryBrands')->name('categories.brands.sync');
    Route::get('categories/{category}/edit-brands', 'CategoryBrandController@editCategoryBrands')->name('categories.brands.edit');
    Route::get('categories/{category}/category-types', 'CategoryController@getCategoryTypes')->name('categories.types.index');
    Route::resource('/categories', 'CategoryController');
});
