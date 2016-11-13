<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    //
    function add(){
        $is_logged_in=user_ins()->is_logged_in();
        if(!$is_logged_in)
            err('login required');
            //return ['status'=>0,'msg'=>'login required'];

        if(!rq('title'))
            err('title required');
            //return ['status'=>0,'msg'=>'title required'];

        $this->title=rq('title');
        $this->user_id=session('user_id');
        if(rq('desc'))
            $this->desc=rq('desc');

        return $this->save()?
            suc(['id'=>$this->id]):
            err('insert db failed');
           // ['status'=>1,'id'=>$this->id]:
           // ['status'=>0,'msg'=>'insert db failed'];
    }

    function change()
    {
        /*检查是否登录*/
        $is_logged_in = user_ins()->is_logged_in();
        if (!$is_logged_in)
            return err('login required');
           // return ['status' => 0, 'msg' => 'login required'];

        /*检查是否有id*/
        if (!rq('id'))
            return err('id request');
           // return ['status' => 0, 'msg' => 'id request'];

        /*根据id获取question model*/
        $question = $this->find(rq('id'));
        if (!$question)
            return err('question not exists');
            //return ['status'=>0,'msg'=>'question not exists'];

        /*检查是否有权限更新*/
        if($question->user_id!=session('user_id'))
            return err('permission denied');
           // return ['status'=>0,'msg'=>'permission denied'];

        /*检查是否有标题*/
        if(rq('title'))
            $question->title=rq('title');

        /*检查是否有描述*/
        if(rq('desc'))
            $question->desc=rq('desc');

        /*保存并返回结果*/
        return $question->save()?
            suc():
            err('update db failed');
            //['status'=>1]:
            //['status'=>0,'msg'=>'update db failed'];
    }

    function read(){
        if (rq('id'))
            return suc(['data'=>$this->find(rq('id'))]);
           // return ['status'=>1,'data'=>$this->find(rq('id'))];

        list($skip,$limit)=paginate(rq('page'),rq('limit'));

        $r=$this
            ->orderBy('created_at')
            ->limit($limit)
            ->skip($skip)
            ->get(['id','title','desc','user_id','created_at','updated_at'])
            ->keyBy('id')
        ;
        return suc(['data'=>$r]);
        //return ['status'=>1,'data'=>$r];
    }

    /*删除问题api*/
    function remove(){
        /*检查是否登录*/
        $is_logged_in = user_ins()->is_logged_in();
        if (!$is_logged_in)
            return suc(['msg' => 'login required']);
           // return ['status' => 0, 'msg' => 'login required'];

        /*检查是否有id*/
        if (!rq('id'))
           // return ['status' => 0, 'msg' => 'id request'];

        /*根据id获取question model*/
        $question = $this->find(rq('id'));
        if (!$question) return ['status'=>0,'msg'=>'question not exists'];

        /*检查是否有权限删除*/
        if($question->user_id!=session('user_id'))
            return ['status'=>0,'msg'=>'permission denied'];

        /*执行删除并返回结果*/
        return $question->delete()?
            suc():
            err('db delete fail');
            //['status'=>0,'msg'=>'db delete fail'];
    }
}
