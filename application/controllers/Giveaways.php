<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Giveaways extends CI_Controller {

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
	    //加载类库
        $this->load->database();
        $this->load->library('export');
        $this->load->model('courses_model');
        $this->load->model('giveaways_model');
        $this->load->model('students_model');
        //参数获取
        $wechatid = $this->input->get_post('wechatid');
        //判断用户是否存在
        $student = $this->students_model->getStudentByWechatUnionId($wechatid);
        if (empty($student)) {
            $this->export->error(401, "no student info in system");
        }
        //判断是否可抽奖
        $courseId = $this->courses_model->isOnCourse();
        if ($courseId == false) {
            $this->export->error(450, "not on course time");
        }
        $currentNodes = $this->giveaways_model->getNodesByCourseId($courseId);
        if ($currentNodes != false) {
            $this->export->error(451, "no chance");
        }
        //业务逻辑
        $sendNodesCount = mt_rand(1, 200);//生成范围内随机整数
        $env = ENVIRONMENT == 'production' ? 'mainnet' : 'testnet';

        //todo 调试智能合约
//        $cmd = "node contracts.js {$student['wallet_address']} {$sendNodesCount} {$env}";
        $cmd = "pwd";//测试用,待以上智能合约可用时,即可被替换
        exec($cmd, $output, $return_var);
        if ($return_var != 0) {
            $this->export->error(550, "send node failed");
        }
        $data = array(
            'course_id' => $courseId,
            'student_id' => '1',
            'nodes' => $sendNodesCount,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
            'is_sent' => 1,
        );
        $res = $this->db->insert('giveaways', $data);
        //结果处理与返回
        if ($res == false) {
            $this->export->operateFailed();
        } else {
            $data = array(
                'nodes' => $sendNodesCount,
            );
            $this->export->ok($data);
        }
	}


}
