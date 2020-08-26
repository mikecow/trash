<?php


namespace app\index\controller;
use app\index\model\Score as scoremodel;

class Score extends Common
{
    public function index(){
        if(session('role') == 0){
            $details = db('detail')->select();
        }else if(session('role') == 1){
            $details = db('detail')->where('user_id', session('id'))->select();
        }

        if(request()->isPost()){
            $data = input('post.');
            $model = new scoremodel();
            $details = $model->filter($data);
            if(!$details){
                $this->error('没有符合的记录！', url('index'));
            }
        }
        foreach ($details as &$d){
            $d['user'] = db('user')->where('id', $d['user_id'])->find();
        }

        $this->assign('details', $details);
        return view();
    }
}