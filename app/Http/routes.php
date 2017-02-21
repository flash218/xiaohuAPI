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

function question_ins(){
    return $user = new App\Question();
}


Route::get('api',function () {
    return ['version'=>'1.1'];
});

Route::post('api/user',function () {
    return user_ins()->signup();
});

Route::post('api/login',function () {
    return user_ins()->login();
});

Route::get('api/logout',function () {
    return user_ins()->logout();
});

Route::post('api/question/add',function (){
    return question_ins()->add();
});

Route::post('api/question/change',function (){
    return question_ins()->change();
});

Route::post('api/question/read',function (){
    return question_ins()->read();
});
