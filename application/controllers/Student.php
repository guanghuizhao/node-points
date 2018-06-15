<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends CI_Controller
{
    //支持前端多次修改的信息key,对应数据库字段
    private $userKeys = "name,phone,wallet_address,nickname,profile";

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

    /**
     * exit 退出登录
     */
    public function logout()
    {
        $this->load->database();
        $this->load->library('export');
        $this->load->library('session');
        $this->load->model('students_model');
        $sessionId = $this->input->get_post('sessionid');

        $this->session->unset_userdata($sessionId);
        $this->export->ok();
    }

    /**
     * 根据用户code获取openid和头像
     * 如果未注册则将openid和头像保存到数据库
     * 如果已注册,则只需返回session_id即可
     * login
     */
    public function login()
    {
        //加载
        $this->load->database();
        $this->load->library('session');
        $this->load->library('export');
        $this->load->library('wechat');
        $this->load->model('students_model');
        //参数与处理
        $code = $this->input->get_post('code');
        if (empty($code)) {
            $this->export->paramError();
        }
        //获取微信中用户openid和头像
        $weChatInfo = $this->wechat->code2session($code);
        if (empty($weChatInfo)) {
            $this->export->error(501, "get wechat openid failed");
        }
        $openid = $weChatInfo['openid'];
        //生成给客户端用的sessionId,并保存映射关系到session中
        $sessionId = md5($openid . time());//生成随机id,提供给客户端
        $this->session->set_tempdata($sessionId, $openid, 60*60*24);//设置过期时间为1天

        //如果openid未保存,则保存到数据库
        $student = $this->students_model->getStudentByOpenId($openid);
        if (empty($student)) {
            $insertData = array(
                'openid' => $openid,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            );
            $res = $this->students_model->insert($insertData);
            if ($res == false) {
                $this->export->operateFailed();
            }
            $data = array(
                'bind' => 0,
                'sessionid' => $sessionId,
            );
            $this->export->ok($data);
        } else {
            $data = array(
                'bind' => $student->phone ? 1 : 0,
                'sessionid' => $sessionId,
            );
            $this->export->ok($data);
        }
    }

    /**
     * 根据用户code获取openid和头像
     * 如果未注册则将openid和头像保存到数据库
     * 如果已注册,则只需返回session_id即可
     * login
     */
    public function bindInfo()
    {
        //加载
        $this->load->database();
        $this->load->library('session');
        $this->load->library('export');
        $this->load->library('wechat');
        $this->load->model('students_model');
        //参数与处理
        $updateInfo = array();
        foreach (explode(",", $this->userKeys) as $key) {
            if ($this->input->get_post($key)) {
                $updateInfo[$key] = $this->input->get_post($key);
            }
        }
        if (empty($updateInfo)) {
            $this->export->paramError();
        }
        $sessionId = $this->input->get_post('sessionid');
        if (empty($sessionId)) {
            $this->export->error(405, "invalid sessionid");
        }
        //根据sessionid获取用户openid
        $openid = $this->session->userdata($sessionId);
        if (empty($openid)) {
            $this->export->error(405, "invalid sessionid");
        }
        $openid = "123a";
        $student = $this->students_model->getStudentByOpenId($openid);
        //注册之前应当已经有openid记录
        if (empty($student)) {
            $this->export->error(403, "student not login");
        }

        $updateInfo['updated_at'] = date("Y-m-d H:i:s");
        $res = $this->students_model->updateByOpenid($openid, $updateInfo);
        if ($res == false) {
            $this->export->operateFailed();
        } else {
            $this->export->ok();
        }
    }


    public function getInfo()
    {
        $this->load->database();
        $this->load->library('export');
        $this->load->library('session');
        $this->load->model('students_model');
        $sessionId = $this->input->get_post('sessionid');
        if (empty($sessionId)) {
            $this->export->error(405, "invalid sessionid");
        }
        //根据sessionid获取用户openid
        $openid = $this->session->userdata($sessionId);
        if (empty($openid)) {
            $this->export->error(405, "invalid sessionid");
        }
        $student = $this->students_model->getStudentByOpenId($openid);
        unset($student->id);
        unset($student->openid);
        if (!empty($student)) {
            $this->export->ok($student);
        } else {
            $this->export->operateFailed();
        }
    }

}

