<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // 用户注册API ***************************
    public function signup()
    {
        $has_username_and_password = $this->has_username_and_password();

        if (!$has_username_and_password)
            return ['status'=>1,'msg'=>'用户名和密码皆不可为空'];
        $username = $has_username_and_password[0];
        $password = $has_username_and_password[1];

        /*检查用户名是否存在*/
        $user_exists = $this->where('username',$username)->exists();

        if ($user_exists)
            return ['status'=>0,'msg'=>'用户名已存在'];

        /*加密密码*/
        $hashed_password = bcrypt($password);

        /*存入数据库*/
        $user = $this;
        $user->username = $username;
        $user->password = $hashed_password;
        if ($user->save())
            return ['status'=>1,'id'=>$user->id,'msg'=>'用户注册成功！'];
        else
            return ['status'=>0,'msg'=>'用户注册失败！'];
    }

    //封装经常出现的方法函数
    protected function has_username_and_password(){
        $username = rq('username');
        $password = rq('password');

        /*检查用户名是否为空*/
        if($username && $password)
            return [$username,$password];
        return false;
    }

}
