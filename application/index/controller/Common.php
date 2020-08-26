<?php


namespace app\index\controller;

use think\Controller;

class Common extends Controller
{
    public function _initialize(){
        if(!session('id') && !session('name')){
            $this->error('请登陆！', url('user/login'));
        }
    }
}