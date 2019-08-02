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
        $value['key'] = $value['id'];
        $value['title'] = $value['name'];
        $items[$value['id']] = $value;
    }
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
        }
    }
    return $tree;
}

/**
 * 无限极分类
 * @param $array
 * @param $node
 * @return array
 */
function generateTreeCode($array, $node)
{
    //第一步 构造数据
    $items = array();
    foreach ($array as $value) {
        $items[$value['code']] = $value;
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

function generateTreeCode1($array, $node)
{
    //第一步 构造数据
    $items = array();
    foreach ($array as $value) {
        $items[$value['code']] = $value;
    }

    //第二部 遍历数据 生成树状结构
    $tree = array();
    //遍历构造的数据
    foreach ($items as $key => $value) {
        //如果pid这个节点存在
        if (isset($items[$value[$node]])) {
            $items[$value[$node]]['children'][] = &$items[$key];
        } else {
            $tree[] = &$items[$key];
        }
    }
    return $tree;
}


/**
 * 得到新订单号
 * @return  string
 */
function get_order_sn()
{
    /* 选择一个随机的方案 */
    mt_srand((double) microtime() * 1000000);

    return date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
}

function assoc_unique($arr, $key)
{
    $tmp_arr = array();
    foreach($arr as $k => $v)
    {
        if(in_array($v[$key], $tmp_arr))//搜索$v[$key]是否在$tmp_arr数组中存在，若存在返回true
        {
            unset($arr[$k]);
        }
        else {
            $tmp_arr[] = $v[$key];
        }
    }
    sort($arr); //sort函数对数组进行排序
    return $arr;
}
