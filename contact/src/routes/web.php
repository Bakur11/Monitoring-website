<?php

use Illuminate\Http\Request;

Route::group(['middleware' => ['web']], function () {

    Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function () {
        Route::get('/', 'Auth\LoginController@showLoginForm');
        Route::post('login', 'Auth\LoginController@login');
        Route::post('logout', 'Auth\LoginController@logout');
    });

    Route::middleware(['auth', 'checkActive'])->group(function () {
        Route::get('/home', 'HomeController@index')->name('home');
        Route::get('/all-links', 'LinkController@allLinks');
        Route::get('/get-link/{id}', 'LinkController@getLink');
        Route::post('/change', 'LinkController@change');
        Route::get('/report/{id}', 'ReportController@reports');
        Route::get('/reports/{id}', 'ReportController@linkReports');
    });

    Route::group(['namespace' => 'Site', 'prefix' => 'admin'], function () {
        Route::get('/', 'Auth\LoginController@showLoginForm');
        Route::post('login', 'Auth\LoginController@login');
        Route::post('logout', 'Auth\LoginController@logout');
    });
    Route::get('/report/{id}', 'Monitoring\Contact\Http\Controllers\ReportController@reports');
    Route::get('/reports/{id}', 'Monitoring\Contact\Http\Controllers\ReportController@linkReports');
    Route::group(['namespace' => 'Monitoring\Contact\Http\Controllers'], function (){
        Route::resource('/links', 'LinkController');
        Route::get('/all-links', 'LinkController@allLinks');
        Route::get('/get-link/{id}', 'LinkController@getLink');
        Route::post('/change', 'LinkController@change');
        Route::get('/home', 'HomeController@index')->name('home');
//    Route::get('/emailActive/{token}', 'ActivateController@index');
//    Route::middleware(['auth', 'checkActive'])->group(function () {
//        Route::get('/home', 'HomeController@index')->name('home');
//    });
    });
});
Route::get('contact', 'Monitoring\Contact\Http\Controllers\ContactController@index')->name('contact');
//Route::post('/register', 'Monitoring\Contact\Http\Controllers\RegisterController@register');




