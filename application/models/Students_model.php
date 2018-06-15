<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: zhaoguanghui03@baidu.com
 * Date: 2018/5/19
 * Time: 下午10:28
 */
class Students_model extends CI_Model
{
    private $tableName = 'students';

    public function getStudentById($id)
    {
        $this->load->database();
        $query = $this->db->get_where($this->tableName, array('id' => $id));
        return $query->row();
    }

    public function getStudentByPhone($phone)
    {
        $this->load->database();
        $query = $this->db->get_where($this->tableName, array('phone' => $phone));
        return $query->row();
    }


    public function getStudentByOpenId($openid)
    {
        $this->load->database();
        $query = $this->db->get_where($this->tableName, array('openid' => $openid));
        return $query->row();
    }

    public function getWalletAddress($id)
    {
        $this->load->database();
        $query = $this->db->select('wallet_address')
            ->where('id', $id)
            ->limit(1)
            ->get($this->tableName);

        return $query->row()->wallet_address;
    }

    public function updateByOpenid($openid, $updateInfo)
    {
        $this->load->database();
        $this->db->where('openid', $openid);
        $ret = $this->db->update($this->tableName, $updateInfo);
        return $ret;
    }

    public function update($student)
    {
        $this->load->database();
        return $this->db->replace($this->tableName, $student);
    }

    public function insert($student)
    {
        $this->load->database();
        return $this->db->insert($this->tableName, $student);
    }

    /**
     * getStudentIdAll 获取学生id和地址,条件为地址非空
     * @return bool|array
     */
    public function getIdAddressAll()
    {
        $this->load->database();
        $query = $this->db->select('id,wallet_address')
            ->where("wallet_address is not null and wallet_address != ''")
            ->get($this->tableName);
        if ($query == false) {
            return false;
        }
        $idAddressMap = array();
        foreach ($query->result() as $row) {
            $idAddressMap[$row->id] = $row->wallet_address;
        }
        return $idAddressMap;
    }
}