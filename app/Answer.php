<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    /*添加回答*/
    function add(){
        /*检查是否登录*/
        $is_logged_in = user_ins()->is_logged_in();
        if (!$is_logged_in)
            return suc(['msg' => 'login required']);

        /*检查是否同时有question_id和content*/
        if (!rq('question_id') || !rq('content'))
            return err('question_id and content are required');
            //return ['status' => 0, 'msg' => 'question_id and content are required'];

        /*检查问题是否存在*/
        $question = question_ins()->find(rq('question_id'));
        if (!$question)
            return err('question not exists');
            //return ['status' => 0, 'msg' => 'question not exists'];

        /*检查是否重复回答*/
        $answered = $this
            ->where(['question_id' => rq('question_id'), 'user_id' => session('user_id')])
            ->count();
        if($answered)
            return err('duplicate answers');
            //return ['status'=>0,'msg'=>'duplicate answers'];

        /*保存到数据库,并返回结果*/
        $this->content=rq('content');
        $this->user_id=session('user_id');
        $this->question_id=rq('question_id');

        return $this->save()?
            suc(['id'=>$this->id]):
            err(['msg'=>'insert db fail']);
           // ['status'=>1,'id'=>$this->id]:
           // ['status'=>0,'msg'=>'insert db fail'];
    }

    /*更新回答api*/
    function change(){
        /*检查是否登录*/
        $is_logged_in = user_ins()->is_logged_in();
        if (!$is_logged_in)
            return suc(['msg' => 'login required']);

        /*检查是否同时有id和content*/
        if (!rq('id') || !rq('content'))
            return err('id and content are required');
           // return ['status' => 0, 'msg' => 'id and content are required'];

        /*检查问题是否存在*/
        $answer = answer_ins()->find(rq('id'));
        if (!$answer)
            return err('answer not exists');
            //return ['status' => 0, 'msg' => 'answer not exists'];

        if($answer->user_id!=session('user_id'))
            return err('permission denied');
            //return ['status' => 0, 'msg' => 'permission denied'];

        $answer->content=rq('content');

        return $answer->save()?
            suc():
            err('update db fail');
            //['status'=>1]:
            //['status'=>0,'msg'=>'update db fail']
            ;
    }

    /*查看问题的答案*/
    function read(){
        /*检查是否传入问题id或者question_id*/
        if (!rq('question_id') && !rq('id'))
            return err('question_id or id  is required');
            //return ['status' => 0, 'msg' => 'question_id or id  is required'];

        /*如果传入回答id,查询数据库，返回结果*/
        if(rq('id')) {
            $answer=$this->find(rq('id'));
            if(!$answer)
                return err('answer not exists');
            return suc(['data'=>$answer]);
                //return ['status'=>0,'msg'=>'answer not exists'];
            //return ['status'=>1,'data'=>$answer];
        }

        /*检查是否存在该问题*/
        $question = question_ins()->find(rq('question_id'));
        if (!$question)
            return err('question not exists');
            //return ['status' => 0, 'msg' => 'question not exists'];

        /*查看同一问题下的所有回答*/
        $answer=$this
            ->where('question_id',rq('question_id'))
            ->get()
            ->keyBy('id');

        return ['status'=>1,'data'=>$answer];
    }

    function vote(){
        /*检查是否登录*/
        $is_logged_in = user_ins()->is_logged_in();
        if (!$is_logged_in)
            return suc(['msg' => 'login required']);

        /*检查参数*/
        if(!rq('answer_id') || !rq('vote'))
            return err('answer_id and vote are required');
            //return ['status'=>0,'msg'=>'answer_id and vote are required'];

        /*投票 1为同意 2为反对*/
        $vote=rq('vote')<=1?1:2;

        /*检查答案是否存在*/
        $answer=$this->find(rq('answer_id'));
        if(!$answer)
            err('answer not exists');
           // return ['status'=>0,'msg'=>'answer not exists'];

        /*检查是否已经对该答案投过票,如果有删除原来的投票记录*/
        $answer
            ->users()
            ->newPivotStatement()
            ->where('user_id',session('user_id'))
            ->where('answer_id',rq('answer_id'))
            ->delete();

        /*记录新的投票*/
        $answer
            ->users()
            ->attach(session('user_id'),['vote'=>$vote]);

        /*返回结果*/
        return suc();
    }

    /*绑定user表*/
    function users(){
        return $this
            ->belongsToMany('App\User')
            ->withPivot('vote') //需要的中间表字段
            ->withTimestamps(); //中间表时间戳一并更新
    }
}
