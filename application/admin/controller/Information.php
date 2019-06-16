<?php
/**
 * Created by PhpStorm.
 * User: xuliulei
 * Date: 18-8-21
 * Time: 下午2:33
 */

namespace app\admin\controller;

use app\admin\common\Check;
use app\admin\common\Util;
use app\admin\db\CDBInformation;
use think\Request;

class Information extends Base
{

    /**
     * 添加分类
     */
    public function addCategory()
    {
        $userId = $GLOBALS['userId'];
        $data = Request::instance()->param();
        $name = Check::check($data['name'] ?? '');
        $pid = Check::checkInteger($data['pid'] ?? '');
        $informationDB = new CDBInformation();
        $result = $informationDB->addCategory($name, $pid, $userId);
        $res['insertRow'] = $result;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $res);
    }

    /**
     * 获取分类列表 树形
     */
    public function getCategory()
    {
        $informationDB = new CDBInformation();
        $result = $informationDB->getCategory();
        $tree = generateTree($result);
        $data['tree'] = $tree;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    public function childId($id)
    {
        $informationDB = new CDBInformation();
        $data = $informationDB->getCategory();
        return $this->getChildId($data, $id);
    }

    public function getChildId($data, $pid)
    {
        static $ret = array();
        foreach ($data as $k => $v) {
            if ($v['pid'] == $pid) {
                $ret[] = $v['id'];
                $this->getChildId($data, $v['id']);
            }
        }
        return $ret;
    }

    /**
     * 删除分类
     */
    public function delCategory()
    {
        $postData = Request::instance()->param();
        $categoryId = Check::checkInteger($postData['categoryId'] ?? '');
        $childId = $this->childId($categoryId);
        array_push($childId, intval($categoryId));

        $informationDB = new CDBInformation();
        $delRow = $informationDB->delCategory($childId);

        $data['delRow'] = $delRow;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 更新分类
     */
    public function updateCategory()
    {
        $postData = Request::instance()->param();
        $categoryId = Check::checkInteger($postData['categoryId'] ?? '');
        $name = Check::check($postData['name'] ?? '');
        $userId = $GLOBALS['userId'];
        $informationDB = new CDBInformation();
        $updateRow = $informationDB->updateCategory($categoryId, $name,  $userId);
        $data['updateRow'] = $updateRow;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 添加热门资讯
     */
    public function addInformation()
    {
        $postData = Request::instance()->param();
        $userId = $GLOBALS['userId'];
        $author = Check::check($postData['author'] ?? '');
        $categoryId = Check::checkInteger($postData['categoryId'] ?? '');
        $title = Check::check($postData['title'] ?? '');
        $content = Check::check($postData['content'] ?? '');
        $isMd = Check::checkInteger($postData['isMd'] ?? 0); //默认不是
        $informationDB = new CDBInformation();
        $insertRow = $informationDB->addInformation($author, $categoryId, $title, $content, $isMd, $userId);
        $data['insertRow'] = $insertRow;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 编辑热门资讯
     */
    public function updateInformation()
    {
        $postData = Request::instance()->param();
        $userId = $GLOBALS['userId'];
        $informationId = Check::checkInteger($postData['informationId'] ?? '');
        $author = Check::check($postData['author'] ?? '');
        $categoryId = Check::checkInteger($postData['categoryId'] ?? '');
        $title = Check::check($postData['title'] ?? '');
        $content = Check::check($postData['content'] ?? '');
        $isMd = Check::checkInteger($postData['isMd'] ?? 0); //默认不是
        $informationDB = new CDBInformation();
        $updateRow = $informationDB->updateInformation($informationId, $author, $categoryId, $title, $content, $isMd, $userId);
        $data['updateRow'] = $updateRow;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 获取所有的热门资讯
     */
    public function getAllInformation()
    {
        $informationDB = new CDBInformation();
        $result = $informationDB->getAllInformation();
        $data['list'] = $result;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 分页获取热门资讯
     */
    public function getInformationPaging()
    {
        $postData = Request::instance()->param();
        $pageIndex = Check::checkInteger($postData['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($postData['pageSize'] ?? 10);
        $informationDB = new CDBInformation();
        $result = $informationDB->getInformationPaging($pageIndex, $pageSize);
        $data['page'] = $result;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }


    /**
     * 根据id删除热门资讯
     */
    public function delInformationById()
    {
        $postData = Request::instance()->param();
        $informationId = Check::checkInteger($postData['informationId'] ?? '');
        $userId = $GLOBALS['userId'];
        $informationDB = new CDBInformation();
        $delRow = $informationDB->delInformation($informationId, $userId);
        $data['delRow'] = $delRow;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 根据id获取资讯详情
     */
    public function getInformationDetail(){
        $postData = Request::instance()->param();
        $informationId = Check::checkInteger($postData['informationId'] ?? '');
        $informationDB = new CDBInformation();
        $detail = $informationDB->getInformationById($informationId);
        $data['detail'] = $detail;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }
}
