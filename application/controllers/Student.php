<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *        http://example.com/index.php/welcome
     *    - or -
     *        http://example.com/index.php/welcome/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */

    public function exist()
    {
        $this->load->database();
        $this->load->library('export');
        $this->load->model('students_model');
        $wechatid = $this->input->get_post('wechatid');

        $student = $this->students_model->getStudentByWechatUnionId($wechatid);

        $this->export->ok(
            array(
                'exist' => $student ? true : false
            )
        );
        return;
    }


    public function register()
    {
        $this->load->database();
        $this->load->library('export');
        $this->load->model('students_model');
        $wechatid = $this->input->get_post('wechatid');
        $phone = $this->input->get_post('phone');
        $name = $this->input->get_post('name');

        $student = $this->students_model->getStudentByWechatUnionId($wechatid);
        //绑定过微信id的，报错
        if ($student) {
            $this->export->error(403, "student registered");
        }

        //通过之前方式绑定的，只需要更新微信id
        $student = $this->students_model->getStudentByPhone($phone);

        if ($student) {
            $student->wechat_unionId = $wechatid;
            $student->updated_at = date("Y-m-d H:i:s");

            $res = $this->students_model->update($student);
            if ($res == 1) {
                $this->export->ok();
            } else {
                $this->export->operateFailed();
            }
        };


        $student = array(
            "phone" => $phone,
            "name" => $name,
            "wechat_unionId" => $wechatid,
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s"),
        );

        $res = $this->students_model->insert($student);
        if ($res == 1) {
            $this->export->ok();
        } else {
            $this->export->operateFailed();
        }
    }

    public function address()
    {
        $this->load->database();
        $this->load->library('export');
        $this->load->model('students_model');

        $wallet_address = $this->input->get_post('address');
        $wechatid = $this->input->get_post('wechatid');
        $id = $this->input->get_post('id');

        if ($id == "" && $wechatid == "") {
            $this->export->paramError();
            return;
        }

        //没有地址的请求是获取地址
        if ($wallet_address == "") {
            $this->export->ok(
                array(
                    'address' => $this->students_model->getStudentByWechatUnionId($wechatid)->wallet_address
                )
            );
        }

        $student = $this->students_model->getStudentByWechatUnionId($wechatid);
        //没有学生信息
        if (!$student) {
            $this->export->error(401, "no student info in system");
        }

        //已填写qtum
        if ($student->wallet_address != "") {
            $this->export->error(402, "preAddress not empty, update fail");
        }

        //结果处理与返回
        $student->wallet_address = $wallet_address;
        $student->updated_at = date("Y-m-d H:i:s");
        $res = $this->students_model->update($student);

        if ($res == 1) {
            $this->export->ok();
        } else {
            $this->export->operateFailed();
        }
    }

}

