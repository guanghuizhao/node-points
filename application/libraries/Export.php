<?php

/**
 * Created by PhpStorm.
 * User: zhaoguanghui03@baidu.com
 * Date: 2018/5/19
 * Time: 下午10:28
 */
class Export
{
    public function ok()
    {
        $output = array(
            'code'   => 0,
            'message'  => 'ok',
        );
        echo json_encode($output);
        exit;
    }
    public function paramError()
    {
        $output = array(
            'code'   => 100,
            'message'  => '参数错误',
        );
        echo json_encode($output);
        exit;
    }

    public function operateFailed()
    {
        $output = array(
            'code'   => 200,
            'message'  => '操作错误',
        );
        echo json_encode($output);
        exit;
    }


    public function error($code,$message)
    {
        $output = array(
            'code'   => $code,
            'message'  => $message,
        );
        echo json_encode($output);
        exit;
    }
}