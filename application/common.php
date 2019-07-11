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


function getChildId($data, $pid)
{
    static $ret = array();
    foreach ($data as $k => $v) {
        if ($v['pid'] == $pid) {
            $ret[] = $v['id'];
            getChildId($data, $v['id']);
        }
    }
    return $ret;
}


/**
 * 无限极分类
 * @param $array
 * @param $node
 * @return array
 */
function generateTree($array, $node)
{
    //第一步 构造数据
    $items = array();
    foreach ($array as $value) {
        $items[$value['id']] = $value;
    }
    //第二部 遍历数据 生成树状结构
    $tree = array();
    //遍历构造的数据
    foreach ($items as $key => $value) {
        //如果pid这个节点存在
        if (isset($items[$value[$node]])) {
            //把当前的$value放到pid节点的son中 注意 这里传递的是引用 为什么呢？
            $items[$value[$node]]['son'][] = &$items[$key];
        } else {
            $tree[] = &$items[$key];
        }
    }
    return $tree;
}

function generateTree1($array, $node)
{
    //第一步 构造数据
    $items = array();
    foreach ($array as $value) {
//        var_dump($value);
        $value['key'] = $value['id'];
        $value['title'] = $value['name'];
        $items[$value['id']] = $value;
    }
//    var_dump($items);
    //第二部 遍历数据 生成树状结构
    $tree = array();
    //遍历构造的数据
    foreach ($items as $key => $value) {
        //如果pid这个节点存在
        if (isset($items[$value[$node]])) {
            //把当前的$value放到pid节点的son中 注意 这里传递的是引用 为什么呢？
            $items[$key]['isLeaf'] = true;
            $items[$value[$node]]['children'][] = &$items[$key];
        } else {
            $tree[] = &$items[$key];
//            var_dump($tree);
        }
    }
    return $tree;
}


/**
 * 遍历文件夹
 * @param $dirname
 */
function bl_scandir($dirname)
{

    $dirArr = scandir($dirname);

    $filenameArr = array();
    foreach ($dirArr as $v) {
        //组合文件或文件夹的路径
        $filename = $dirname . '\\' . $v;

        if ($v != '.' && $v != '..') {
            if (is_dir($v)) {
                bl_scandir($v);
            } else {
                array_push($filenameArr, $filename);
            }
        }
    }
    return $filenameArr;
}
