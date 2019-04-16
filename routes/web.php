<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
Route::get('login/{id}', 'Auth\LoginController@redirectToProvider')->name('social_auth');
Route::get('login/{driver}/callback', 'Auth\LoginController@handleProviderCallback');

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::get('/set_languate/{lang}', 'Controller@setLanguage')->name('set_language');

Route::get('/images/{path}/{attachment}', function ($path, $attachment) {
    $file = sprintf('storage/%s/%s', $path, $attachment);

    if (File::exists($file)) {
        return Image::make($file)->response();
    }

    //MOSCA CON EL STORAGE LINK
});

Route::group(['prefix' => 'courses'], function () {
    Route::get('/{course}', 'CourseController@show')->name('courses.detail');
});
