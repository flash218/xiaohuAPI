<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

//封装经常出现的方法函数
function rq($key=null,$default=null){
    if(!$key)
        return Request::all();
    return Request::get($key,$default);
}

function user_ins(){
    return $user = new App\User();
}

Route::get('api',function () {
    return ['version'=>'1.1'];
});

Route::post('api/user',function () {
    return user_ins()->signup();
});
