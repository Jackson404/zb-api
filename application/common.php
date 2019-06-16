<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------


function currentTime()
{
    return date('Y-m-d H:i:s', time());
}

function getRandLengthStr($length = 8)
{
// 密码字符集，可任意添加你需要的字符
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
// 这里提供两种字符获取方式
// 第一种是使用 substr 截取$chars中的任意一位字符；
// 第二种是取字符数组 $chars 的任意元素
// $password .= substr($chars, mt_rand(0, strlen($chars) – 1), 1);
        $password .= $chars[mt_rand(0, strlen($chars) - 1)];
    }
    return $password;
}
/**
 * 递归实现无限极分类
 * @param $array 分类数据
 * @param $pid 父ID
 * @param $level 分类级别
 * @return $list 分好类的数组 直接遍历即可 $level可以用来遍历缩进
 */

function getTree($array, $pid =0, $level = 0){

    //声明静态数组,避免递归调用时,多次声明导致数组覆盖
    static $list = [];
    foreach ($array as $key => $value){
        //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
        if ($value['pid'] == $pid){
            //父节点为根节点的节点,级别为0，也就是第一级
            $value['level'] = $level;
            //把数组放到list中
            $list[] = $value;
            //把这个节点从数组中移除,减少后续递归消耗
            unset($array[$key]);
            //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
            getTree($array, $value['id'], $level+1);

        }
    }
    return $list;
}

function generateTree($array){
    //第一步 构造数据
    $items = array();
    foreach($array as $value){
        $items[$value['id']] = $value;
    }
    //第二部 遍历数据 生成树状结构
    $tree = array();
    //遍历构造的数据
    foreach($items as $key => $value){
        //如果pid这个节点存在
        if(isset($items[$value['pid']])){
            //把当前的$value放到pid节点的son中 注意 这里传递的是引用 为什么呢？
            $items[$value['pid']]['son'][] = &$items[$key];
        }else{
            $tree[] = &$items[$key];
        }
    }
    return $tree;
}
