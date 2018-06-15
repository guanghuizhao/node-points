<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crontab extends CI_Controller {

    //crontab配置方式:
    //第一步:输入命令:crontab -e
    //第二步:编辑一行配置,如下(每五分钟执行一次,cd到网站根目录,需要根据实际目录调整)
    // */5 * * * * cd /home/wwwroot/default && php index.php crontab updateNode >> log/updateNode.log
    /**
     * 定时脚本,用于离线更新Node积分总数
     * 手动运行方式(在项目根目录下):php index.php crontab updateNode
	 */
    public function updateNode()
    {
        $this->load->database();
        $this->load->library('export');
        $this->load->library('node');
        $this->load->model('students_model');
        $this->load->model('noderank_model');

        //只允许脚本执行
        if (!is_cli()) {
            $this->export->paramError();
        }

        $idAddressMap = $this->students_model->getIdAddressAll();
        if (empty($idAddressMap)) {
            $this->export->error(510, "sql query failed" . date("Y-m-d H:i:s"));
        }

        $failList = array();
        foreach ($idAddressMap as $id => $address) {
            $count = $this->node->queryNodeCount($address);
            if ($count === false) {
                $failList['node_query'][] = $id;
                continue;
            }
            $ret = $this->noderank_model->updateNode($id, $count);
            if ($ret === false) {
                $failList['db_update'][] = $id;
            }
        }
        $p['failList'] = $failList;
        $p['datetime'] = date("Y-m-d H:i:s");
        if (empty($failList)) {
            $this->export->ok($p);
        } else {
            $this->export->error('510', $p);
        }
    }

}
