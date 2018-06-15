<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sign extends CI_Controller
{

    /**
     * 课程签到
     */
    public function index()
    {
        $this->load->database();
        $this->load->library('export');
        $this->load->library('session');
        $this->load->library('node');
        $this->load->library('map');
        $this->load->model('courses_model');
        $this->load->model('coursesigns_model');
        $this->load->model('students_model');
        $sessionId    = $this->input->get_post('sessionid');
        $studentPoint = $this->input->get_post('point');
        if (empty($sessionId)) {
            $this->export->error(405, "invalid sessionid");
        }
        if (empty($studentPoint) || strpos($studentPoint, ",") === false) {
            $this->export->error(405, "invalid point");
        }
        //根据sessionid获取用户openid
        $openid = $this->session->userdata($sessionId);
        var_dump($openid);
        if (empty($openid)) {
            $this->export->error(405, "invalid sessionid");
        }
//        $openid = "123a";

        $student = $this->students_model->getStudentByOpenId($openid);
        //注册之前应当已经有openid记录
        if (empty($student)) {
            $this->export->error(403, "student not login");
        }
        //判断是否可签到
        $courseId = $this->courses_model->isOnCourse();
        if ($courseId == false) {
            $this->export->error(450, "not on course time");
        }
        //获取课程详情
        $courseInfo = $this->courses_model->getCourseById($courseId);
        //判断是否已经签过到
        $currentNodes = $this->coursesigns_model->getNodesByCourseId($courseId, $student->id);
        if ($currentNodes != false) {
            $this->export->error(451, "no chance");
        }
        //判断学生是否在教室范围内
        $isInRange = $this->map->isInCourseRange($courseInfo->point, $studentPoint);
        if (!$isInRange) {
            $this->export->error(452, "not in course range");
        }
        //发送积分
        $sendNodesCount = $courseInfo->sign_nodes === null ? $courseInfo->sign_nodes : 350;//获取数据库里配置的积分数量,默认350
        $sendRet        = $this->node->sendNode($student->wallet_address, $sendNodesCount);
        if ($sendRet === false) {
            $this->export->error(550, "send node failed");
        }
        $data = array(
            'course_id'      => $courseId,
            'student_id'     => $student->id,
            'received_nodes' => $sendNodesCount,
            'created_at'     => date("Y-m-d H:i:s"),
            'updated_at'     => date("Y-m-d H:i:s"),
            'is_sent'        => 1,
        );
        $res  = $this->coursesigns_model->insert($data);
        //结果处理与返回
        if ($res == false) {
            $this->export->operateFailed();
        }
        else {
            $data = array(
                'nodes' => $sendNodesCount,
            );
            $this->export->ok($data);
        }
    }

}

