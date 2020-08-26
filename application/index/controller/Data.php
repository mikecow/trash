<?php


namespace app\index\controller;
use app\index\model\Data as datamodel;

class Data extends Common
{
    public function collect(){
        $model = new datamodel();
        $locates = db('locate')->select();
        $this->assign('locates', $locates);
        $collects = db('recycle')->where('status', 0)->select();

        if(request()->isPost()){
            $data = input('post.');
            $collects = $model->collect($data);
            if(!$collects){
                $this->error('没有符合条件的记录！', url('collect'));
            }
        }

        $model->changetype($collects);
        $model->changeloc_id($collects);
        $this->assign('collects', $collects);
        return view();
    }

    public function done($id){
        $data = db('recycle')->where('id', $id)->find();
        $data['status'] = 1;
        $res = db('recycle')->update($data);

        switch($data['type']){
            case 1:
                $map = db('map')->where('string', "kehuishou")->find();
                $map['value']+= $data['weight'];
                $res2 = db('map')->update($map);
                break;
            case 2:
                $map = db('map')->where('string', "youdu")->find();
                $map['value']+= $data['weight'];
                $res2 = db('map')->update($map);
                break;
            case 3:
                $map = db('map')->where('string', "youji")->find();
                $map['value']+= $data['weight'];
                $res2 = db('map')->update($map);
                break;
        }
        if($res && $res2){
            $this->success('操作成功！', url('collect'));
        }else{
            $this->error('操作失败！', url('collect'));
        }
    }

    public function order(){
        $model = new datamodel();
        $locates = db('locate')->select();
        $this->assign('locates', $locates);
        $orders = db('recycle')->where('status', 1)->select();

        if(request()->isPost()){
            $data = input('post.');
            $orders = $model->collect($data);
            if(!$orders){
                $this->error('没有符合条件的记录！', url('order'));
            }
        }

        $model->changetype($orders);
        $model->changeloc_id($orders);
        $this->assign('orders', $orders);
        return view();
    }

    public function score(){
        $red = db('user')->where('role', 1)->order('score desc')->limit(10)->select();
        $black = db('user')->where('role', 1)->order('score')->limit(10)->select();
        foreach ($red as &$d) {
            $loc = db('locate')->where('id', $d['locate_id'])->find();
            $d['locate_id'] = $loc['name'];
        }
        foreach ($black as &$d) {
            $loc = db('locate')->where('id', $d['locate_id'])->find();
            $d['locate_id'] = $loc['name'];
        }
        $this->assign('red', $red);
        $this->assign('black', $black);
        return view();
    }


}