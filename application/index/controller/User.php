<?php


namespace app\index\controller;
use app\index\model\User as Usermodel;
use think\Controller;

class User extends Controller
{
    public function login(){
        if(request()->isPost()){
            $data = input('post.');
            $model = new Usermodel();
            $res = $model->login($data);
            if($res){
                $this->success('登录成功', url('index/index'));
            }else{
                $this->error('登录失败');
            }
        }
        return view();
    }

    public function logout(){
        session('id', null);
        session('role', null);
        session('name', null);
        $this->success('退出登录！', url('login'));
    }

    public function index(){
        $user = db('user')->where('id', session('id'))->find();
        $place = db('locate')->where('id', $user['locate_id'])->find();
        $locates = db('locate')->select();

        $this->assign('user', $user);
        $this->assign('locates', $locates);
        $this->assign('place', $place['name']);
        return view();
    }

    public function register(){
        if(request()->isPost()){
            $data = input('post.');
            $model = new Usermodel();
            $res = $model->register($data);
            if($res){
                $this->success('注册成功', url('login'));
            }else{
                $this->error('注册失败');
            }
        }
        $locates = db('locate')->select();
        $this->assign('locates', $locates);
        return view();
    }

    public function saveuser(){
        if(request()->isPost()){
            $data = input('post.');
            $model = new Usermodel();
            $res = $model->saveUser($data);
            if($res){
                $this->success('修改成功', url('index'));
            }else{
                $this->error('修改失败');
            }
        }
    }

    public function sellorg(){
        if(request()->isPost()){
            $data = input('post.');
            $model = new Usermodel();
            $res = $model->sellorg($data);
            if($res){
                $this->success('添加成功', url('index'));
            }else{
                $this->error('添加失败');
            }
        }
    }

    public function addtrash(){
        if(request()->isPost()){
            $data = input('post.');
            $file = request()->file('thumb');
            $model = new Usermodel();
            $res = $model->addtrash($data, $file);
            if($res){
                $this->success('添加成功', url('index'));
            }else{
                $this->error('添加失败');
            }
        }
    }

    public function editfac(){
        if(request()->isPost()){
            $data = input('post.');
            $model = new Usermodel();
            $res = $model->editfac($data);
            if($res){
                $this->success('修改成功', url('index'));
            }else{
                $this->error('修改失败');
            }
        }
    }

    public function addlocate(){
        if(request()->isPost()){
            $data = input('post.');
            $res = db('locate')->insert($data);
            if($res){
                $this->success('添加成功！', url('index'));
            }else{
                $this->error('添加失败！');
            }
        }
    }

    public function addadmin(){
        if(request()->isPost()){
            $data = input('post.');
            $model = new Usermodel();
            $res = $model->addadmin($data);
            if($res){
                $this->success('添加成功！', url('index'));
            }else{
                $this->error('添加失败！');
            }
        }
    }

}