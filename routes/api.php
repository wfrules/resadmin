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


//Route::group(['middleware' => 'token'], function(){
//    Route::post('register', 'API\UserController@register');
//});


//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    throw new \Exception('wftest');
//    return $request->user();
//});

Route::any('/user/getprofile', 'Api\UserController@getProfile');//获取用户资料
Route::post('/user/register', 'Api\UserController@register');//获取用户资料
Route::any('/user/login', 'Api\UserController@login');//自动会加api前缀
Route::any('/user/saveprofile', 'Api\UserController@saveProfile');//自动会加api前缀
Route::any('/train/submit', 'Api\TrainController@submit');//自动会加api前缀
Route::any('/train/debug', 'Api\TrainController@debug');//自动会加api前缀
Route::any('/train/getsum', 'Api\TrainController@getSum');//自动会加api前缀
Route::any('/train/getpool', 'Api\TrainController@getPool');//题库池
Route::any('/train/getquest', 'Api\TrainController@getQuest');//题库池
Route::any('/train/getstat', 'Api\TrainController@getStat');//获取图表总统计
Route::any('/train/reset', 'Api\TrainController@reset');//自动会加api前缀
Route::any('/train/debug', 'Api\TrainController@debug');//自动会加api前缀
Route::any('/sys/agent', 'Api\SysController@agent');//自动会加api前缀
