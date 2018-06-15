<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: zhaoguanghui03@baidu.com
 * Date: 2018/5/19
 * Time: 下午10:28
 */
class NodeRank_model extends CI_Model
{
    private $tableName = 'node_rank';


    public function getRankList($start, $num)
    {
        $this->load->database();
        $sql = "select s.name,s.profile,s.nickName,r.total ".
        "from {$this->tableName} as r inner join students as s on r.student_id=s.id ".
        "order by r.total desc limit $start, $num;";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getTotal()
    {
        $this->load->database();
        return $this->db->count_all_results($this->tableName);
    }

    public function getSelfRank($studentId)
    {
        $this->load->database();
        $innerSql = "select total from {$this->tableName} where student_id = $studentId";
        $sql = "select count(id) as self_rank ".
            "from {$this->tableName} ".
            "where total > ($innerSql);";
//        $query = $this->db->query($sql);

        $result = $this->db->query($sql)->row();
        return $result->self_rank + 1;
    }

    public function insert($node)
    {
        $this->load->database();
        return $this->db->insert($this->tableName, $node);
    }

    public function updateNode($studentId, $nodeCount)
    {
        $this->load->database();
        $query = $this->db->get_where($this->tableName, array('student_id' => $studentId));
        if (empty($query->row())) {
            $insertData = array(
                'student_id' => $studentId,
                'total' => $nodeCount,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            );
            $ret = $this->db->insert($this->tableName, $insertData);
            return $ret;
        } else {
            $ret = $this->db->set('total', $nodeCount, FALSE)
                ->where('student_id', $studentId)
                ->update($this->tableName);
            return $ret;
        }
    }
}