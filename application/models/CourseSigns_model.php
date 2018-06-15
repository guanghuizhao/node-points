<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: zhaoguanghui03@baidu.com
 * Date: 2018/5/19
 * Time: 下午10:28
 */
class CourseSigns_model extends CI_Model
{
    private $tableName = 'course_signs';

    /**
     * getNodesByCourseId 获取某学生在某节课获取积分情况,用于判断是否重复签到
     * @param $courseId
     * @param $studentId
     * @return mixed
     */
    public function getNodesByCourseId($courseId, $studentId)
    {
        $this->load->database();
        $query = $this->db->get_where($this->tableName, array('course_id' => $courseId, 'student_id' => $studentId));
        $rowObj = $query->row();
        if (!empty($rowObj)) {
            return $rowObj->received_nodes;
        } else {
            return false;
        }
    }

    public function insert($data)
    {
        $this->load->database();
        return $this->db->insert($this->tableName, $data);
    }
}