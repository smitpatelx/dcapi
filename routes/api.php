<?php

use Illuminate\Http\Request;

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

Route::post('/login', 'AuthController@Login');
Route::post('/attendance', 'AttendancesController@store');
Route::get('/attendance', 'AttendancesController@index');
Route::get('/getstudentid', 'StudentsController@getstudentid');
Route::get('/secret','SecretWordsController@index');

Route::middleware(['auth:api'])->group(function () {
    Route::post('/password/update', 'AuthController@ChangePassword');
    Route::get('/users', 'AuthController@getUsersList');
    Route::delete('/user/delete', 'AuthController@DeleteUser');
    Route::post('/logout','AuthController@Logout');
    Route::post('/register','AuthController@Register');
    Route::resource('students', 'StudentsController')->only([
        'index', 'update', 'destroy','store'
    ]);
    Route::post('/secret/create','SecretWordsController@store');
    Route::patch('/secret/update/{id}','SecretWordsController@update');
});
