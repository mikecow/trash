<?php


namespace app\index\model;


use think\Model;

class Data extends Model
{
    public function collect($data){
        if($data['date']) {
            $time = substr($data['date'], 6, 4);
            $time .= '-';
            $time .= substr($data['date'], 0, 2);
            $time .= '-';
            $time .= substr($data['date'], 3, 2);
            $data['date'] = $time;
        }

        if($data['type'] == 0){
            unset($data['type']);
        }

        foreach ($data as $key => &$value){
            if(!$value){
                unset($data[$key]);
            }
        }

        return db('recycle')->where($data)->where('status', 0)->select();
    }

    public function order($data){
        if($data['date']) {
            $time = substr($data['date'], 6, 4);
            $time .= '-';
            $time .= substr($data['date'], 0, 2);
            $time .= '-';
            $time .= substr($data['date'], 3, 2);
            $data['date'] = $time;
        }

        if($data['type'] == 0){
            unset($data['type']);
        }

        foreach ($data as $key => &$value){
            if(!$value){
                unset($data[$key]);
            }
        }

        return db('recycle')->where($data)->where('status', 1)->select();
    }

    public function changetype(&$data){
        foreach ($data as &$d) {
            switch ($d['type']) {
                case "1":
                    $d['type'] = "可回收垃圾";
                    break;
                case '2':
                    $d['type'] = "有毒有害垃圾";
                    break;
                case "3":
                    $d['type'] = "有机垃圾";
                    break;
            }
        }
    }

    public function changeloc_id(&$data){
        foreach ($data as &$d) {
            $loc = db('locate')->where('id', $d['loc_id'])->find();
            $d['loc_id'] = $loc['name'];
        }
    }


}