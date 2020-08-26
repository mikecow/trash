<?php


namespace app\index\controller;
use app\index\model\Control as controlmodel;

class Control extends Common
{
    public function addfac(){
        if(request()->isPost()){
            $data = input('post.');
            $model = new controlmodel();
            $res = $model->addfac($data);
            if($res){
                $this->success('添加成功！');
            }else{
                $this->error('添加失败！');
            }
        }
        return view();
    }

    public function buy(){
        $model = new controlmodel();
        $locates = db('locate')->select();
        $this->assign('locates', $locates);

        if(request()->isPost()){
            $data = input('post.');
            $res = $model->buy($data);
            if($res){
                $model->addlocaname($res);
                $this->assign('orgs', $res);
                return view();
            }else{
                $res = db('org')->select();
                $model->addlocaname($res);
                $this->assign('orgs', $res);
                $this->error('没有符合的记录', url('buy'));
            }
        }
        $res = db('org')->select();
        $model->addlocaname($res);
        $this->assign('orgs', $res);
        return view();
    }

    public function detail(){
        $model = new controlmodel();
        $locates = db('locate')->select();
        $this->assign('locates', $locates);
        $fac = db('factory')->where('user_id', session('id'))->find();
        $this->assign('fac', $fac);
        $details = db('factorybuy')->where('fac_id', $fac['id'])->select();
        $model->addlocaname($details);
        $this->assign('details', $details);

        if(session('role') == 0){
            // 管理员查看的是所有订单
            $details = db('factorybuy')->select();
            $model->addlocaname($details);
            $this->assign('details', $details);
        }

        if(request()->isPost()){
            $data = input('post.');
            if(session('role') == 2){
                $res = $model->detail($data);
            }else{
                $res = $model->alldetail($data);
            }

            if($res){
                $model->addlocaname($res);
                $this->assign('details', $res);
                return view();
            }else{
                $this->error('没有符合的记录！', url('detail'));
            }

        }

        return view();
    }

    public function delorg($id){
        $res = db('org')->delete($id);
        if($res){
            $this->success('删除成功！', url('buy'));
        }else{
            $this->success('删除失败！', url('buy'));
        }
    }

    public function buyorg(){
        if(request()->isPost()){
            $data = input('post.');
            $model = new controlmodel();
            $res = $model->buyorg($data);
            if($res){
                $this->success('购买成功！');
            }else{
                $this->error('购买失败！');
            }
        }
    }
}