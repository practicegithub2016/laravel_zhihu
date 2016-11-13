<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
    Route::get('/', function () {
        return view('welcome');
    });

    Route::any('api', function () {
        return ['version' => 0.1];
    });

    Route::any('api/signup', function () {
        return user_ins()->signup();
    });

    Route::any('api/login', function () {
        return user_ins()->login();
    });

    Route::any('api/logout', function () {
        return user_ins()->logout();
    });

    //更改密码
    Route::any('api/user/change_password', function () {
        return user_ins()->change_password();
    });

    //找回密码
    Route::any('api/user/reset_password', function () {
        return user_ins()->reset_password();
    });

    //是否已经登录
    Route::any('api/is_logged_in', function () {
        dd(user_ins()->is_logged_in());
    });

    Route::any('api/question/add', function () {
        return question_ins()->add();
    });

    Route::any('api/question/change', function () {
        return question_ins()->change();
    });

    Route::any('api/question/read', function () {
        return question_ins()->read();
    });

    Route::any('api/question/remove', function () {
        return question_ins()->remove();
    });

    Route::any('api/answer/add', function () {
        return answer_ins()->add();
    });

    Route::any('api/answer/change', function () {
        return answer_ins()->change();
    });

    Route::any('api/answer/read', function () {
        return answer_ins()->read();
    });

    Route::any('api/answer/vote', function () {
        return answer_ins()->vote();
    });

    Route::any('api/comment/add', function () {
        return comment_ins()->add();
    });

    Route::any('api/comment/read', function () {
        return comment_ins()->read();
    });

    Route::any('api/comment/remove', function () {
        return comment_ins()->remove();
    });

    Route::any('api/timeline','CommonController@timeline');
});

    function user_ins()
    {
        return new App\User;
    }

    function question_ins()
    {
        return new App\Question;
    }

    function answer_ins()
    {
        return new App\Answer;
    }

    function comment_ins()
    {
        return new App\Comment;
    }

    /*简化请求参数函数*/
    function rq($key=null,$default=null)
    {
        if(!$key) return Requests::all();
        return Request::get($key,$default);
    }

    /*处理分页参数函数*/
    function paginate($page=1,$limit=15)
    {
        $limit=$limit?:15;
        $skip=($page?$page-1:0)*$limit;
        return [$skip,$limit];
    }

    /*简化错误的返回函数*/
    function err($msg=null){
        return ['status'=>0,'msg'=>$msg];
    }

    function suc($data_to_merge=null){
        $data= ['status'=>1];
        if($data_to_merge)
            $data=array_merge($data,$data_to_merge);
        return $data;
    }

