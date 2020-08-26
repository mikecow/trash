<?php
namespace app\index\controller;

class Index extends Common
{
    public function index()
    {
        $user = db('user')->where('id', session('id'))->find();

        if($user['role'] == 0){
            $user['role'] = "管理员";
        }else if($user['role'] == 1){
            $user['role'] = "村民用户";
        }else{
            $name = db('factory')->where('user_id', session('id'))->find();
            $user['role'] = $name['name']."公司主理人";
        }

        $locate_name = db('locate')->where('id', $user['locate_id'])->find();
        $user['locate'] =  $locate_name['name'] . " " .$user['locate'];
        $this->assign('user', $user);

        $youdu = db('map')->where('string', 'youdu')->find();
        $this->assign('youdu', $youdu['value']);

        $youji = db('map')->where('string', 'youji')->find();
        $this->assign('youji', $youji['value']);

        $kehuishou = db('map')->where('string', 'kehuishou')->find();
        $this->assign('kehuishou', $kehuishou['value']);

        $input = db('map')->where('string', 'input')->find();
        $this->assign('input', $input['value']);

        $yest = date("Y-m-d",strtotime("-1 day"));
        $recycle = db('recycle')->where('date', $yest)->where('status', 1)->select();
        $buy = db('factorybuy')->where('date', $yest)->select();

        $yifu = db('recycle')->where('status', 0)->where('type', 3)->select();
        foreach ($yifu as &$d) {
            $loc = db('locate')->where('id', $d['loc_id'])->find();
            $d['loc_id'] = $loc['name'];
        }

        $type1 = $type2 =  $type3 = 0;
        foreach ($recycle as $d){
            if($d['type'] == 1){
                $type1 += $d['weight'];
            }elseif ($d['type'] == 2){
                $type2 += $d['weight'];
            }elseif ($d['type'] == 3){
                $type3 += $d['weight'];
            }
        }

        $yestoutput = 0;
        foreach ($recycle as $d){
            if($d['type'] == 3){
                $yestoutput += $d['weight'];
            }
        }

        $yestinput = 0;
        foreach ($buy as $d){
            $yestinput+= $d['weight'];
        }

        $red = db('user')->where('role', 1)->order('score desc')->limit(5)->select();

        $this->assign('yestoutput', $yestoutput);
        $this->assign('yestinput', $yestinput);
        $this->assign('type1', $type1);
        $this->assign('type2', $type2);
        $this->assign('type3', $type3);
        $this->assign('yifu', $yifu);
        $this->assign('red', $red);
        return view();
    }

}
