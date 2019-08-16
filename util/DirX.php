<?php

namespace Util;
/**
 * Author:xuliulei
 *
 *
 * -概述
 *    通过执行getDirExplorer函数进行遍历目录操作。在遍历目录的同时，可采用注册动作函数的方式对每个文件进行相关动作处理。
 *
 * -功能：
 *    1 遍历目录下的所有文件（可指定后缀名）
 *    2 批量替换文件内容（正则、字符串）
 *    3 批量替换文件后缀名
 *    4 批量替换文件编码
 *
 * -介绍和使用例：
 *    1 public函数
 *        Array getDirExplorer (string $dirPath [, string  $extension=''] [, Array $actionArray=array()])
 *        返回值：
 *            遍历后得到的文件名数组
 *        参数说明:
 *            $dirPath    【必须参数】需要遍历的文件夹
 *            $extension    【可选参数】指定后缀名 只返回指定后缀名的文件
 *            $actionArray    【可选参数】储存对文件进行相关操作的函数、参数的数组。此数组可以是以下几种值之一或其组合：
 * $actionArray = array(
 *                    'modifyByIreplace'    => array($search, $replace),        忽略大小写的文本替换
 *                    'modifyByReplace'    => array($search, $replace)，        不忽略大小写的文本替换
 *                    'modifyByRegex'        => array($search, $replace)，        正则表达式进行文本替换
 *                    'changeCharset'        => array($inCharset, $outCharset)，        改变文件的字符编码
 *                    'changeExtension'    => array($inExtension, $outExtension)    改变文件的后缀名
 *                )
 *
 *    2 例：
 *        下面的代码将遍历目录【F:/test/】下的所有文件（不指定后缀名），并对其下文件中后缀名为TXT的修改为txt，然后将所有文件的字符编码从UTF-16变更为UTF-8，
 *        最后将文件内容中的tester替换为test。
 *        (注意:替换文本内容时此源码文件的编码和目标文件编码应该一致。比如此DirExplorerClass源码是utf-8，
 *        所以在下例中替换tester的操作是在目标文件已修改为utf-8之后进行的。)
 *        $actionArray = array(
 *            'changeExtension'=>array('TXT','txt'),
 *            'changeCharset'=>array('UTF-16','UTF-8'),
 *            'modifyByIreplace'=>array('tester','test')
 *        );
 *        $dirExplorer = new DirExplorerClass();
 *        $dirExplorer->getDirExplorer('F:/test/', '', $actionArray)；
 */
class DirX
{
    private $_dirPathArray = array();//遍历文件结果集合

    /**
     *  construct
     */
    function __construct()
    {
        //donothing
    }

    /**
     *  __get - 获取私有属性
     */
    public function __get($propertyName)
    {
        return ($this->$propertyName);
    }

    /**
     *  getDirExplorer - 遍历指定目录，返回其下的所有文件名或指定后缀的文件名集合。并在遍历过程中可对文件进行相关操作。
     *
     * @param dirPath - 需要遍历的文件夹
     * @param extension - 指定后缀名 只返回指定后缀名的文件
     * @param actionArray - 储存对文件进行相关操作的函数、参数的数组
     *
     * @return 遍历文件结果集合
     */
    public function getDirExplorer($dirPath, $extension = '', $actionArray = array())
    {
        $dirHander = null;
        $codeArray = array();    //存储执行动作的代码

        //input check
        if (is_dir($dirPath) && is_array($actionArray)) {
            $dirHander = opendir($dirPath);
        } else {
            return false;
        }

        foreach ($actionArray as $actionFun => $paramArray) {
            if (method_exists($this, '_' . $actionFun) && is_array($paramArray) && count($paramArray) > 0) {
                $codeArray[] = '$this->_' . $actionFun . '(\'' . implode('\', \'', $paramArray) . '\', $path);';
            } else {
                //do nothing
            }
        }

        //遍历文件夹操作 + 处理文件的各种动作
        while ($fileName = readdir($dirHander)) {
            if ($fileName != '.' && $fileName != '..') {
                $path = $dirPath . "/" . $fileName;
                if (is_dir($path)) {
                    $this->getDirExplorer($path);
                } else {
                    if (isset($extension) && $extension != '') {
                        $fileExtension = substr(strrchr($fileName, '.'), 1);
                        if ($fileExtension != $extension) {
                            continue;
                        }
                    }

                    //处理文件的各种动作
                    foreach ($codeArray as $code) {
                        @eval($code);
                    }

                    $this->_dirPathArray[] = $path;
                }
            }
        }
        closedir($dirHander);
        return $this->_dirPathArray;
    }

    /**
     *  _modifyByIreplace - 字符串替换文件内容（忽略大小写）
     *
     * @param search - 需要替换的字符串 （数组可)
     * @param replace - 替换后的字符串 （数组可)
     * @param file - 指定的文件
     *
     * @return - true or false
     */
    private function _modifyByIreplace($search, $replace, $file)
    {
        //input check
        if (!isset($search) || !isset($replace)) {
            return false;
        }

        $content = file_get_contents($file);
        $content = str_ireplace($search, $replace, $content);
        file_put_contents($file, $content);
        unset($content);

        return true;
    }

    /**
     *  _modifyByReplace- 字符串替换文件内容（区别大小写）
     *
     * @param search - 需要替换的字符串 （数组可)
     * @param replace - 替换后的字符串 （数组可)
     * @param file - 指定的文件
     *
     * @return - true or false
     */
    private function _modifyByReplace($search, $replace, $file)
    {
        /* input check */
        if (!isset($search) || !isset($replace)) {
            return false;
        }

        $content = file_get_contents($file);
        $content = str_replace($search, $replace, $content);
        file_put_contents($file, $content);
        unset($content);

        return true;
    }

    /**
     *  _modifyByRegex - 正则替换文件内容
     *
     * @param search - 需要替换内容的正则表达式
     * @param replace - 替换后的字符串
     * @param file - 指定的文件
     *
     * @return - true or false
     */
    private function _modifyByRegex($search, $replace, $file)
    {
        // input check
        if (!isset($search) || !isset($replace)) {
            return false;
        }
        if (preg_match('!([a-zA-Z\s]+)$!s', $search, $match) && (strpos($match[1], 'e') !== false)) {
            //remove eval-modifier from $search
            $search = substr($search, 0, -strlen($match[1])) . preg_replace('![e\s]+!', '', $match[1]);
        }

        $content = file_get_contents($file);
        $content = preg_replace($search, $replace, $content);
        file_put_contents($file, $content);
        unset($content);

        return true;
    }

    /**
     *  _changeCharset - 变换编码
     *
     * @param inCharset - 原编码
     * @param outCharset - 新编码
     * @param file - 指定的文件
     *
     * @return - true or false
     */
    private function _changeCharset($inCharset = '', $outCharset = '', $file)
    {
        /* input check */
        if (strlen($inCharset) == 0 || strlen($outCharset) == 0) {
            return false;
        }

        $content = file_get_contents($file);
        $content = iconv($inCharset, $outCharset, $content);
        unlink($file);
        file_put_contents($file, $content);
        unset($content);

        return true;
    }

    /**
     *  _changeExtension - 变换后缀名
     *
     * @param inExtension - 原后缀名
     * @param outExtension - 新后缀名
     * @param file - 指定的文件
     *
     * @return - true or false
     */
    private function _changeExtension($inExtension = '', $outExtension = '', &$file)
    {
        //inout check
        if (strlen($inExtension) == 0 || strlen($outExtension) == 0) {
            return false;
        }

        $divide = strripos($file, '.');
        if ($divide === false) {
            return false;
        }
        $nowExtension = substr($file, $divide + 1);
        $beforePart = substr($file, 0, $divide);

        if ($nowExtension == $inExtension) {
            $content = file_get_contents($file);
            unlink($file);
            $file = $beforePart . '.' . $outExtension;
            file_put_contents($file, $content);
            unset($content);
        }

        return true;
    }

}