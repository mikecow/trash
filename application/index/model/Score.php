<?php


namespace app\index\model;


use think\Model;

class Score extends Model
{
    public function filter($data){
        if($data['date']){
            $time = substr($data['date'], 6, 4);
            $time .= '-';
            $time .= substr($data['date'], 0, 2);
            $time .= '-';
            $time .= substr($data['date'], 3, 2);
            $data['date'] = $time;
        }
        //判断身份
        $role = session('role');
        if($role == 1){
            if($data['date']){
                if($data['type'] == 0){
                    return db('detail')->where('user_id', session('id'))->where('date', $data['date'])->where('gain', '>', 0)->select();
                }else{
                    return db('detail')->where('user_id', session('id'))->where('date', $data['date'])->where('gain', '<', 0)->select();
                }
            }else{
                if($data['type'] == 0){
                    return db('detail')->where('user_id', session('id'))->where('gain', '>', 0)->select();
                }else{
                    return db('detail')->where('user_id', session('id'))->where('gain', '<', 0)->select();
                }
            }
        }else{
            if($data['date']){
                if($data['type'] == 0){
                    return db('detail')->where('date', $data['date'])->where('gain', '>', 0)->select();
                }else{
                    return db('detail')->where('date', $data['date'])->where('gain', '<', 0)->select();
                }
            }else{
                if($data['type'] == 0){
                    return db('detail')->where('gain', '>', 0)->select();
                }else{
                    return db('detail')->where('gain', '<', 0)->select();
                }
            }
        }
    }
}