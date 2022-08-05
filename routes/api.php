<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('sn/routes', function (Request $request) {
    return collect(Route::getRoutes())->map(function ($route) { return $route->uri(); });
});

Route::post('crop', function (Request $request) {
   return response()->json($request->file('cropped'));
});


