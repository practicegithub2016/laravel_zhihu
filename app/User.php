<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Request;
use Hash;

class User extends Model
{
    public function signup(){
        /*检查用户名、密码是否为空*/
        $has_username_and_password = $this->has_username_and_password();
        if (!$has_username_and_password)
            return err('username and password are required');
        list($username,$password)=$has_username_and_password;
        /*检查用户名是否存在*/
        $user_exists=$this
            ->where('username', $username)
            ->exists();
        if( $user_exists )
        {
            return err('username is exists');
        }

        /*密码加密*/
        $hashed_password=bcrypt($password);

        /*存入数据库*/
        $user=$this;
        $user->password=$hashed_password;
        $user->username=$username;
        if( $user->save() )
        {
            return suc(['id'=>$user->id]);
           // return ['status'=>1, 'id'=>$user->id];
        }else
        {
            return err('db insert fail');
           // return ['status'=>0, 'msg'=>'db insert fail'];
        }
    }

    function login(){
        /*检查用户名、密码是否为空*/
        $has_username_and_password = $this->has_username_and_password();
        if (!$has_username_and_password)
            err('username and password are required');
            //return ['status' => 0, 'msg' => ' username and password are required'];
        list($username,$password)=$has_username_and_password;

        /*检查用户是否存在*/
        $user=$this->where('username',$username)->first();
        if( !$user )
            return err('username not exist');
            //return ['status'=>0,'msg'=>'username not exist'];

        /*检查密码是否正确*/
        $hashed_password=$user->password;
        if( !Hash::check($password,$hashed_password) )
            return err('invaild password');
            //return ['status'=>0,'msg'=>'invaild password'];

        /*将用户信息写入session*/
        session()->put('username',$user->username);
        session()->put('user_id',$user->id);
        return suc(['msg'=>'login success']);
        //return ['status'=>1,'msg'=>'login success'];
    }

    function change_password(){
        //检查是否登录
        $is_logged_in = user_ins()->is_logged_in();
        if (!$is_logged_in)
            return err('login required');
            //return ['status' => 0, 'msg' => 'login required'];

        //检查参数
        if(!rq('old_password') || !rq('new_password'))
            return err('old_password and new_password are required');
            //return ['status'=>0,'msg'=>'old_password and new_password are required'];

        //验证旧密码
        $user=$this->find(session('user_id'));
        $check=Hash::check(rq('old_password'),$user->password);
        if(!$check)
            return err('invaid old password');
            //return ['status'=>0,'msg'=>'invaid old password'];

        //更新密码
        $user->password=bcrypt(rq('new_password'));
       return $user->save()?
           suc():
           err('db update failed');
    }

    //找回密码api
    function reset_password(){
        //检查参数phone
        if(!rq('phone'))
            return err('phone is required');

        $exists=$this->where('phone',rq('phone'))->exists();

        if(!$exists)
            return err('invalid phone number');
    }

    /*检测是否登录*/
    function is_logged_in()
    {
        return session('user_id')?true:false;
    }

    /*登出*/
    function logout()
    {
        session()->forget('username');
        session()->forget('user_id');
        return suc(['msg'=>'logout success']);
    }

    private function has_username_and_password(){
        $username=rq('username');
        $password=rq('password');
        if(!($username && $password))
            return false;
        return [$username,$password];
    }
}
