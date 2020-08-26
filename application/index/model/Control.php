<?php


namespace app\index\model;


use think\Model;

class Control extends Model
{
    public function addfac($data){
        $user['name'] = $data['user_name'];
        $user['password'] = md5($data['user_password']);
        $user['tele'] = $data['user_tele'];
        $user['shenfen'] = $data['user_shenfen'];
        $user['date'] = date("Y-m-d H:i:s");
        $user['locate'] = $data['fac_locate'];
        $user['role'] = 2;

        $fac['name'] = $data['fac_name'];
        $fac['tele'] = $data['fac_tele'];
        $fac['locate'] = $data['fac_locate'];

        $res = db('user')->insert($user);

        if(!$res){
            return false;
        }

        $user = db('user')->where('name', $user['name'])->find();
        $fac['user_id'] = $user['id'];

        return db('factory')->insert($fac);
    }

    public function buy($data){
        if($data['date']) {
            $time = substr($data['date'], 6, 4);
            $time .= '-';
            $time .= substr($data['date'], 0, 2);
            $time .= '-';
            $time .= substr($data['date'], 3, 2);
            $data['date'] = $time;
        }
        if(!$data['date'] && !$data['locate_id']){
            //此时没有选择直接按下提交
            return db('org')->select();
        }else if($data['date'] && $data['locate_id']){
            //此时两个都有选择提交
            return db('org')->where('locate_id', $data['locate_id'])->where('date', $data['date'])->select();
        }else if($data['date']){
            //只选了日期
            return db('org')->where('date', $data['date'])->select();
        }else{
            //只选了地区
            return db('org')->where('locate_id', $data['locate_id'])->select();
        }

    }

    public function addlocaname(&$data){
        foreach($data as &$a){
            $name = db('locate')->where('id', $a['locate_id'])->find();
            $a['locate'] = $name['name'];
        }
    }

    public function buyorg($data){
        $fac = db('factory')->where('user_id', session('id'))->find();
        $org = db('org')->where('id', $data['org_id'])->find();

        if($data['weight'] > $org['weight']){
            return false;
        }

        $data['fac_id'] = $fac['id'];
        $data['date'] = date("Y-m-d H:i:s");
        $data['price'] = $org['price'] * $data['weight'];
        $data['locate_id'] = $org['locate_id'];
        unset($data['org_id']);

        $org['weight'] -= $data['weight'];
        if($org['weight'] == 0){
            //此时全被买下
            db('org')->where('id', $org['id'])->delete();
        }else{
            db('org')->where('id', $org['id'])->update(array('weight' => $org['weight']));
        }
        $map = db('map')->where('string', 'input')->find();
        $map['value']+= $data['price'];
        db('map')->update($map);
        return db('factorybuy')->insert($data);
    }

    public function detail($data){
        if($data['date']) {
            $time = substr($data['date'], 6, 4);
            $time .= '-';
            $time .= substr($data['date'], 0, 2);
            $time .= '-';
            $time .= substr($data['date'], 3, 2);
            $data['date'] = $time;
        }
        //找到账号下的公司
        $fac = db('factory')->where('user_id', session('id'))->find();

        if(!$data['date'] && !$data['locate_id']){
            //此时没有选择直接按下提交
            return db('factorybuy')->where('fac_id', $fac['id'])->select();
        }else if($data['date'] && $data['locate_id']){
            //此时两个都有选择提交
            return db('factorybuy')->where('fac_id', $fac['id'])->where('locate_id', $data['locate_id'])->where('date', $data['date'])->select();
        }else if($data['date']){
            //只选了日期
            return db('factorybuy')->where('fac_id', $fac['id'])->where('date', $data['date'])->select();
        }else{
            //只选了地区
            return db('factorybuy')->where('fac_id', $fac['id'])->where('locate_id', $data['locate_id'])->select();
        }
    }

    //管理员筛选
    public function alldetail($data){
        if($data['date']) {
            $time = substr($data['date'], 6, 4);
            $time .= '-';
            $time .= substr($data['date'], 0, 2);
            $time .= '-';
            $time .= substr($data['date'], 3, 2);
            $data['date'] = $time;
        }

        if(!$data['date'] && !$data['locate_id']){
            //此时没有选择直接按下提交
            return db('factorybuy')->select();
        }else if($data['date'] && $data['locate_id']){
            //此时两个都有选择提交
            return db('factorybuy')->where('locate_id', $data['locate_id'])->where('date', $data['date'])->select();
        }else if($data['date']){
            //只选了日期
            return db('factorybuy')->where('date', $data['date'])->select();
        }else{
            //只选了地区
            return db('factorybuy')->where('locate_id', $data['locate_id'])->select();
        }
    }
}