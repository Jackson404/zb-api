<?php
///**
// * Created by PhpStorm.
// * User: Administrator
// * Date: 2017/10/31 0031
// * Time: 15:29
// */
//namespace app\admin\common;
//
//class Util {
//    /**
//     * 输出结果的函数
//     * @param success 是否成功 true是成功，false是失败
//     */
//    public static function printResult($errorCode,$data){
//        $result['errorCode']=$errorCode;
//        if(is_array($data)){
//            $result['msg'] = "";
//            $result['data'] = $data;
//        }else{
//            $result['msg'] = $data;
//            $result['data'] = new \stdClass();
//        }
//        echo json_encode($result);
//    }
//
//}