<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feedback extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
        $this->load->database();
        $this->load->library('export');
        $this->load->library('session');
        $this->load->model('students_model');
        //参数获取
        $stars = $this->input->get_post('stars');
        $content = $this->input->get_post('content');
        $sessionId = $this->input->get_post('sessionid');
        //参数处理
        if (empty($stars) || !in_array($stars, array(1,2,3,4,5))) {
            $this->export->paramError();
        }
        if (empty($sessionId)) {
            $this->export->error(405, "invalid sessionid");
        }
        //根据sessionid获取用户openid
        $openid = $this->session->userdata($sessionId);
        if (empty($openid)) {
            $this->export->error(405, "invalid sessionid");
        }
        $student = $this->students_model->getStudentByOpenId($openid);
        //注册之前应当已经有openid记录
        if (empty($student)) {
            $this->export->error(403, "student not login");
        }

        //业务逻辑
        $data = array(
            'student_id' => $student->id,
            'stars' => $stars,
            'content' => $content,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        );

        $res = $this->db->insert('feedbacks', $data);

        //结果处理与返回
        if ($res == false) {
            $this->export->operateFailed();
        } else {
            $this->export->ok();
        }
	}
}
