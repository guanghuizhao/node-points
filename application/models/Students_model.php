<?php

/**
 * Created by PhpStorm.
 * User: zhaoguanghui03@baidu.com
 * Date: 2018/5/19
 * Time: 下午10:28
 */
class Students_model extends CI_Model
{
    public $id;
    public $name;
    public $wallet_address;
    public $phone;
    public $create_at;
    public $update_at;
    public $wechat_unionId;

    public function getStudentById($id)
    {
        $query = $this->db->get_where('students', array('id' => $id));
        return $query->row();
    }
    public function getStudentByWechatUnionId($wechat_unionId)
    {
        $query = $this->db->get_where('students', array('wechat_unionId' => $wechat_unionId));
        return $query->row();
    }

    public function getWalletAddress($id)
    {
        $query = $this->db->select('wallet_address')
            ->where('id', $id)
            ->limit(1)
            ->get('students');

        return $query->row()->wallet_address;
    }
}