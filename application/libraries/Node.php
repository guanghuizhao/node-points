<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: zhaoguanghui03@baidu.com
 * Date: 2018/5/19
 * Time: 下午10:28
 */
class Node
{
    /**
     * sendNode 发送Node积分,调用js合约代码
     * @param string $walletAddress 接收者地址
     * @param int|double $sendNodesCount 数量
     * @return bool
     */
    public function sendNode($walletAddress, $sendNodesCount)
    {
        $env = ENVIRONMENT == 'production' ? 'mainnet' : 'testnet';
        //调试智能合约
        if (ENVIRONMENT == 'development') {
            $cmd = "pwd";//测试用,待以上智能合约可用时,即可被替换
        } else {
            $cmd = "node contracts.js {$walletAddress} {$sendNodesCount} {$env}";
        }
        exec($cmd, $output, $return_var);
        return $return_var != 0 ? false : true;
    }

    //todo 查询Node积分总量,需要依赖查询脚本
    /**
     * queryNodeCount 查询积分
     * @param string $walletAddress 钱包地址
     * @return bool|int 成功返回数量,失败返回false
     */
    public function queryNodeCount($walletAddress)
    {
        $env = ENVIRONMENT == 'production' ? 'mainnet' : 'testnet';
        //todo 调试智能合约
        if (ENVIRONMENT == 'development') {
            $cmd = "ls -l |grep '^-'|wc -l";//测试用,返回当前目录文件个数,即可被替换
        } else {
            $cmd = "node contracts_query.js {$walletAddress} {$env}";
        }
        exec($cmd, $output, $return_var);
        if ($return_var != 0) {
            return false;
        }
        $count = trim($output[0]);//脚本运行打印的第一行的内容,如果脚本调整则需要修改此处获取方式
        if (!is_numeric($count)) {
            return false;
        }
        return $count;
    }

}