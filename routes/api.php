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
Route::resource('menu', 'MenuController');//->middleware('route.auth');
Route::resource('pages', 'PagesController');
Route::delete('pages/section/{id}', 'PagesController@sectionDestroy');
Route::resource('page-section', 'PagesectionController');
Route::get('page/slug', 'PagesController@pageSlug');
Route::post('section', 'SectionController@updateSection');
Route::get('section', 'SectionController@indexSection');
Route::delete('section/{id}', 'SectionController@sectionDelete');
Route::delete('section-property/{id}', 'SectionController@sectionPropertyDelete');
Route::resource('post-category', 'PostCategoryController');
Route::resource('posts', 'PostController');
Route::resource('project-category', 'ProjectCategoryController');
Route::resource('projects', 'ProjectController');
Route::resource('services', 'ServicesController');
Route::delete('services/gallery/{id}', 'ServicesController@deleteGaleryImage');
Route::resource('testimonials', 'TestimonialsController');
Route::middleware('jwt.auth')->get('users', function(Request $request) {
    return auth()->user();
});
