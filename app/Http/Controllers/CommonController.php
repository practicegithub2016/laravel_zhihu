<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class CommonController extends Controller
{
    function timeline(){
        //分页
        list($skip,$limit)=paginate(rq('page'),rq('limit'));

        $questions=question_ins()
            ->limit($limit)
            ->skip($skip)
            ->orderBy('created_at','desc')
            ->get();

        $answers=answer_ins()
            ->limit($limit)
            ->skip($skip)
            ->orderBy('created_at','desc')
            ->get();

        //**bug这里会覆盖相同的id的值
        $data=$questions->merge($answers);

        $data=$data->sortByDesc(function($item){
            return $item->created_at;
        });

        $data=$data->values()->all();

        return ['status'=>1,'data'=>$data];
    }
}
