<?php


namespace app\index\model;


use think\Model;

class Shop extends Model
{
    public function add($data, $file){
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($info){
            // $thumb=ROOT_PATH . 'public' . DS . 'uploads'.'/'.$info->getExtension();
            $thumb='/' . 'public' . DS . 'uploads'.'/'.$info->getSaveName();
            $data['thumb']=$thumb;
            return db('shop')->insert($data);
        }else{
            return false;
        }
    }

    public function buy($id){
        $user = db('user')->where('id', session('id'))->find();
        $shop = db('shop')->where('id', $id)->find();

        if($user['score'] < $shop['price']){
            return false;
        }else{
            $user['score'] -= $shop['price'];
            $res = db('user')->update($user);
            if($res){
                $bus['shop_id'] = $shop['id'];
                $bus['user_id'] = $user['id'];
                $bus['status'] = 0;
                return db('shopbus')->insert($bus);
            }else{
                return false;
            }
        }
    }
}