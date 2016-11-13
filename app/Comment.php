<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    function add()
    {
        /*检查是否登录*/
        $is_logged_in = user_ins()->is_logged_in();
        if (!$is_logged_in)
            return suc(['msg' => 'login required']);

        /*检查参数*/
        if (!rq('question_id') && !rq('answer_id') && !rq('reply_to'))
            return err('question_id or answer_id or reply_to is required');
            //return ['status' => 0, 'msg' => 'question_id or answer_id or reply_to is required'];

        if (rq('question_id')) {
            $question = question_ins()->find(rq('question_id'));
            if (!$question)
               return  err('question not exists');
                //return ['status' => 0, 'msg' => 'question not exists'];
            $this->question_id = rq('question_id');
        }

        if (rq('answer_id')) {
            $answer_id = answer_ins()->find(rq('answer_id'));
            if (!$answer_id)
                return err('answer not exists');
                //return ['status' => 0, 'msg' => 'answer not exists'];
            $this->answer_id = rq('answer_id');
        }

        if (rq('reply_to')) {
            $target = $this->find(rq('reply_to'));
            if (!$target)
                return err('target comment not exists');
                //return ['status' => 0, 'msg' => 'target comment not exists'];
            if ($target->user_id == session('user_id'))
                return err('cannot reply to yourself');
                //return ['status' => 0, 'msg' => 'cannot reply to yourself'];
            if ($target->answer_id)
                $this->answer_id = $target->answer_id;
            elseif ($target->question_id)
                $this->question_id = $target->question_id;
            $this->reply_to = rq('reply_to');
        }

        if (!rq('content'))
            return err('content is required');
            //return ['status' => 0, 'msg' => 'content is required'];

        $this->content = rq('content');
        $this->user_id = session('user_id');
        return $this->save() ?
            suc(['id' => $this->id]):
            err('db insert failed');
            //['status' => 1, 'id' => $this->id] :
            //['status' => 0, 'msg' => 'db insert failed'];
    }

    function read()
    {
        /*检查必要参数*/
        if (!rq('question_id') && !rq('answer_id'))
            return ('question_id or answer_id is required');
            //return ['status' => 0, 'msg' => 'question_id or answer_id is required'];

        //根据问题id查询评论,否则根据回答id查询
        if (rq('question_id')) {
            $question = question_ins()->find(rq('question_id'));
            if (!$question)
                return err('question not exists');
                //return ['status' => 0, 'msg' => 'question not exists'];
            $data = $this->where('question_id', rq('question_id'));
        } else {
            $answer = answer_ins()->find(rq('answer_id'));
            if (!$answer)
                err('answer not exists');
                //return ['status' => 0, 'msg' => 'answer not exists'];
            $data = $this->where('answer_id', rq('answer_id'));
        }

        //返回数据
        return suc(['data' => $data->get()->keyBy('id')]);
        //return ['status' => 1, 'data' => $data->get()->keyBy('id')];
    }

    function remove()
    {
        /*检查是否登录*/
        $is_logged_in = user_ins()->is_logged_in();
        if (!$is_logged_in)
            return suc(['msg' => 'login required']);

        //检查参数
        if(!rq('id'))
            return err('id is required');
            //return ['status'=>0,'msg'=>'id is required'];

        //检查是否存在此回复
        $comment=$this->find(rq('id'));
        if(!$comment)
            return err('comment not exists');
            //return ['status'=>0,'msg'=>'comment not exists'];

        //检查删除权限
        if($comment->user_id!=session('user_id'))
            return ('permission denied');
            //return ['status'=>0,'msg'=>'permission denied'];

        //删除对应此回复的回复
        $this->where('reply_to',rq('id'))->delete();

        //删除回复,并返回结果
        return $comment->delete()?
            suc():
            err('db delete failed');
            //['status'=>0,'msg'=>'db delete failed'];
    }
}
