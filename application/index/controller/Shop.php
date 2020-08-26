<?php


namespace app\index\controller;
use app\index\model\Shop as shopmodel;

class Shop extends Common
{
    public function index(){
        $user = db('user')->where('id', session('id'))->find();
        $this->assign('user', $user);
        $shops = db('shop')->select();
        $this->assign('shops', $shops);

        return view();
    }

    public function add(){
        if(request()->isPost()){
            $data = input('post.');
            $file = request()->file('thumb');
            $model = new shopmodel();
            $res = $model->add($data, $file);
            if($res){
                $this->success('添加成功', url('index'));
            }else{
                $this->error('添加失败');
            }
        }
        return view();
    }

    public function del($id){
        $res = db('shop')->delete($id);
        if($res){
            $this->success('删除成功', url('index'));
        }else{
            $this->error('删除失败');
        }
    }

    public function buy($id){
        $model = new shopmodel();
        $res = $model->buy($id);
        if($res){
            $this->success('购买成功！', url('index'));
        }else{
            $this->error('购买失败！', url('index'));
        }
    }
}