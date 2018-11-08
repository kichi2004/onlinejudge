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


Route::get ('signin', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('signin', 'Auth\LoginController@login');
Route::post('signout', 'Auth\LoginController@logout')->name('logout');

Route::get ('signup', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('signup', 'Auth\RegisterController@register');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', 'MainController@index')->name('top');
    Route::get('problems', 'ProblemController@list')->name('problems');
    Route::get('problems/{id}', 'ProblemController@problem')->where('id', '\d+')->name('problem');
    Route::get('problems/{id}/editorial', 'ProblemController@editorial')->where('id', '\d+')->name('problem_editorial');

    Route::get('submissions/me', 'SubmissionController@mySubmissions')->name('submissions_me');
    Route::get('submissions/{id}', 'SubmissionController@submission')->where('id', '\d+')->name('submission');

    Route::group(['middleware' => ['permission:1']], function () {
        Route::get('submit/{id?}', 'SubmissionController@submitForm')->where('id', '\d+')->name('submit');
        Route::post('submit', 'SubmissionController@submit');
    });

    Route::group(['middleware' => ['permission:2']], function () {});
    Route::group(['middleware' => ['permission:4']], function () {});
    Route::group(['middleware' => ['permission:8']], function () {
        Route::get('submissions', 'SubmissionController@allSubmissions')->name('submissions');
    });


    
});
