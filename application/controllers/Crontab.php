<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crontab extends CI_Controller {

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


        $idAddressMap = $this->students_model->getStudentIdAll();
        if (empty($idList)) {
            $this->export->error(510, "sql query failed");
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

        if (empty($failList)) {
            $this->export->ok();
        } else {
            $this->export->error('510', $failList);
        }
    }

}
