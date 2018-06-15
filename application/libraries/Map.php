<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: zhaoguanghui03@baidu.com
 * Date: 2018/6/15
 * Time: 上午11:56
 */
class Map
{

    /**
     * isInCourseRange 判断是否在范围内
     * @param $coursePoint
     * @param $studentPoint
     * @param int $rangeLimited 距离限制,单位米
     * @return bool
     */
    public function isInCourseRange($coursePoint, $studentPoint, $rangeLimited = 1000)
    {

        list($lng1, $lat1) = array_filter(preg_split("/,|，|、/", $coursePoint));
        list($lng2, $lat2) = array_filter(preg_split("/,|，|、/", $studentPoint));
        $distance = $this->getDistance($lng1, $lat1, $lng2, $lat2);
//        print_r($distance);
        if ($distance > $rangeLimited) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 求两个已知经纬度之间的距离,单位为米
     *
     * @param lng1 ,lng2 经度
     * @param lat1 ,lat2 纬度
     * @return float 距离，单位米
     * @author www.Alixixi.com
     */
    public function getDistance($lng1, $lat1, $lng2, $lat2) {
        // 将角度转为狐度
        $radLat1 = deg2rad($lat1); //deg2rad()函数将角度转换为弧度
        $radLat2 = deg2rad($lat2);
        $radLng1 = deg2rad($lng1);
        $radLng2 = deg2rad($lng2);
        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137 * 1000;
        return $s;
    }
}