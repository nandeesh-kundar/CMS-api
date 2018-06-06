<?php

use Illuminate\Http\Request;
Use App\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {

    return $request->user();
}); 

Route::post('auth/register', 'AuthController@register');
Route::post('auth/login', 'AuthController@login');
Route::group(['middleware' => 'jwt.auth'], function(){
    Route::post('auth/logout', 'AuthController@logout');
 });
Route::middleware('jwt.refresh')->get('/token/refresh', 'AuthController@refresh');


Route::resource('banner-type', 'BannerTypeController');
Route::resource('banner', 'BannerController');
Route::resource('page-property', 'PagePropertyController');
Route::resource('page', 'PagesController');//->middleware('route.auth');
Route::post('page/update', 'PagesController@update')->middleware('route.auth');
Route::middleware('jwt.auth')->get('users', function(Request $request) {
    return auth()->user();
});
