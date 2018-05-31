<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'third_party/wechat/wxBizDataCrypt.php';

/**
 * Created by PhpStorm.
 * User: zhaoguanghui03@baidu.com
 * Date: 2018/5/30
 * Time: 下午4:08
 */
class WeChat
{
    private $appid = '';
    private $secret = '';

    /**
     * code2session
     * @param $code
     * @return bool|mixed
     */
    public function code2session($code)
    {
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$this->appid}&secret={$this->secret}&js_code={$code}&grant_type=authorization_code";
        $data = $this->https_request($url);
        if (!is_array($data)) {
            return false;
        } else {
            return $data;//array('openid'=>'', 'session_key'=>'');
        }
    }

    /**
     * getUserInfo 获取用户基本信息
     * @param $code
     * @return mixed openid,nickName,avatarUrl等
     */
    public function getUserInfo($code)
    {
        $url1 = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->appid}&secret={$this->secret}&js_code={$code}&grant_type=authorization_code";
        $res1 = $this->https_request($url1);
        $data = json_decode($res1, true);
        if (empty($data['openid']) || $data['access_token']) {
            return false;
        }
        //todo 调试接口和返回值
        $url2 = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$data['access_token']."&openid=".$data['openid']."&lang=zh_CN";
        $res2 = $this->https_request($url2);
        $res2 = json_decode($res2, true);
        $res2['openid'] = $data['openid'];
        return $res2;
    }

    public function https_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
}