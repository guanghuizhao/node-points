<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Address extends CI_Controller {

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
		$this->load->model('students_model');

		$wallet_address = $this->input->get_post('address');
		$wechatid = $this->input->get_post('wechatid');
		$id = $this->input->get_post('id');

		if($id == "" && $wechatid == ""){
			$this->export->paramError();
			return;
		}

		//没有地址的请求是获取地址
		if($wallet_address == ""){
            $output = array(
                'code'   => 100,
                'message'  => 'ok',
                'address' => $this->students_model->getStudentByWechatUnionId($wechatid)->wallet_address
            );
            echo json_encode($output);
            return;
		}

		$student = $this->students_model->getStudentByWechatUnionId($wechatid);
		//没有学生信息
		if(!$student){
			$this->export->error(101, "no student info in system");
		}

		//已填写qtum
        if($student->wallet_address != ""){
			$this->export->error(102, "preAddress not empty, update fail");
        }

        $data = array(
			'wallet_address' => $wallet_address,
            'updated_at' => date("Y-m-d H:i:s"),
        );
        $this->db->where('id', $id);
        $res = $this->db->update('students', $data);

        //结果处理与返回
        if ($res == 1) {
            $this->export->ok();
        } else {
            $this->export->operateFailed();
        }
	}

}

