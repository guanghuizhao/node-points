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
	    //加载类库
        $this->load->database();
        //参数获取
        $stars = $this->input->get_post('stars');
        $content = $this->input->get_post('content');
        //参数处理
        if (empty($stars) || !in_array($stars, array(1,2,3,4,5))) {
            $output = array(
                'code'   => 2,
                'message'  => '参数错误',
            );
            echo json_encode($output);
            exit;
        }
        //业务逻辑
        $data = array(
            'student_id' => '1',
            'stars' => $stars,
            'content' => $content,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        );

        $res = $this->db->insert('feedbacks', $data);

        //结果处理与返回
        if ($res == 1) {
            $output = array(
                'code'   => 0,
                'message'  => 'ok',
            );
        } else {
            $output = array(
                'code'   => 1,
                'message'  => '操作失败',
            );
        }
        echo json_encode($output);
	}
}
