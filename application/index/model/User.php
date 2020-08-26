<?php


namespace app\index\model;


use think\Model;

class User extends Model
{
    public function register($data){
        if($data['locate_id'] == 0){
            return false;
        }

        $data['date'] = date("Y-m-d H:i:s");
        //role 用户等级
        //0 网站管理维护者
        //1 普通用户
        //2 公司代表人用户
        $data['role'] = 1;

        //密码md5加密
        $data['password'] = md5($data['password']);

       return db('user')->insert($data);
    }

    public function login($data){
        $data['password'] = md5($data['password']);

        $res = db('user')->where('name', $data['name'])->where('password', $data['password'])->find();

        if(!$res){
            return false;
        }

        session('id', $res['id']);
        session('role', $res['role']);
        session('name', $res['name']);

        return true;
    }

    public function saveUser($data){

        if($data['password']){
            $data['password'] = md5($data['password']);
        }

        foreach($data as $key => $value){
            if($value){
                $res = db('user')->where('id', session('id'))->update([$key => $value]);
                if(!$res){
                    return false;
                }
            }
        }
        return true;
    }

    public function sellorg($data){
        $data['date'] = date("Y-m-d H:i:s");
        return db('org')->insert($data);
    }

    public function addtrash($data, $file){
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($info){
            // $thumb=ROOT_PATH . 'public' . DS . 'uploads'.'/'.$info->getExtension();
            $thumb='/' . 'public' . DS . 'uploads'.'/'.$info->getSaveName();
            $data['thumb']=$thumb;
            $data['date'] = date("Y-m-d H:i:s");
            //垃圾未处理
            $data['status'] = 0;
            return db('recycle')->insert($data);
        }else{
            return false;
        }

    }

    public function editfac($data){
        foreach($data as $key => $value){
            if($value){
                $res = db('factory')->where('user_id', session('id'))->update([$key => $value]);
                if(!$res){
                    return false;
                }
            }
        }
        return true;
    }

    public function addadmin($data){
        $data['role'] = 0;
        $data['date'] = date("Y-m-d H:i:s");
        $data['password'] = md5($data['password']);
        return db('user')->insert($data);
    }

}