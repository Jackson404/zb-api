<?php
/**
 * Created by PhpStorm.
 * User: xuliulei
 * Date: 18-8-22
 * Time: 下午5:33
 */

namespace app\admin\db;

use think\Db;

class CDBInformation
{
    /**
     * 添加分类
     * @param $name
     * @param $pid
     * @param $userId
     * @return int|string
     */
    public function addCategory($name, $pid, $userId)
    {
        $data = [
            'name' => $name,
            'pid' => $pid,
            'create_by' => $userId,
            'create_time' => currentTime(),
            'update_by' => $userId,
            'update_time' => currentTime()
        ];
        $result = Db::name('information_category')->insert($data);
        return $result;
    }

    /**
     * 获取分类列表
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getCategory()
    {
        $result = Db::name('information_category')
            ->where('is_delete', 'eq', 0)
            ->select();
        return $result;
    }

    /**
     * 删除分类
     * @param $ids
     * @return int|string
     */
    public function delCategory($ids)
    {
        $delRow = Db::name('information_category')
            ->whereIn('id', $ids)
            ->update(['is_delete' => 1]);
        return $delRow;
    }

    /**
     * 更新分类
     * @param $categoryId
     * @param $name
     * @param $pid
     * @param $userId
     * @return int|string
     */
    public function updateCategory($categoryId, $name, $userId)
    {
        $updateRow = Db::name('information_category')
            ->where('id', 'eq', $categoryId)
            ->where('is_delete', 'eq', 0)
            ->update(
                [
                    'name' => $name,
                    'update_by' => $userId,
                    'update_time' => currentTime()
                ]
            );
        return $updateRow;
    }

    /**
     * 添加热门资讯
     * @param $author
     * @param $categoryId
     * @param $title
     * @param $content
     * @param $isMd
     * @param $userId
     * @return int|string
     */
    public function addInformation($author, $categoryId, $title, $content, $isMd, $userId)
    {
        $data = [
            'author' => $author,
            'categoryId' => $categoryId,
            'title' => $title,
            'content' => $content,
            'is_md' => $isMd,
            'create_by' => $userId,
            'create_time' => currentTime(),
            'update_by' => $userId,
            'update_time' => currentTime()
        ];
        $insertRow = Db::name('information_hot')->insert($data);
        return $insertRow;
    }

    /**
     * 删除热门资讯
     * @param $informationId
     * @return int|string
     */
    public function delInformation($informationId, $userId)
    {
        $delRow = Db::name('information_hot')
            ->where('id', 'eq', $informationId)
            ->where('create_by', 'eq', $userId)
            ->update(['is_delete' => 1]);
        return $delRow;
    }

    /**
     * 编辑热门资讯
     * @param $informationId
     * @param $author
     * @param $categoryId
     * @param $title
     * @param $content
     * @param $isMd
     * @param $userId
     * @return int|string
     */
    public function updateInformation($informationId, $author, $categoryId, $title, $content, $isMd, $userId)
    {
        $data = [
            'author' => $author,
            'categoryId' => $categoryId,
            'title' => $title,
            'content' => $content,
            'is_md' => $isMd,
            'update_by' => $userId,
            'update_time' => currentTime()
        ];
        $updateRow = Db::name('information_hot')
            ->where('is_delete','eq',0)
            ->where('id', 'eq', $informationId)->update($data);
        return $updateRow;
    }

    /**
     * 获取所有的热门资讯
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getAllInformation()
    {
        $result = Db::name('information_hot')
            ->alias('ih')
            ->join('information_category ic', 'ih.categoryId = ic.id', 'left')
            ->where('ih.is_delete','eq',0)
            ->field('ih.id,ih.author,ih.categoryId,ic.name as categoryName,
            ih.title,ih.content,ih.is_md,ih.create_by,ih.create_time,ih.update_by,ih.update_time')
            ->select();

        return $result;
    }

    /**
     * 分页获取所有的资讯
     * @param $pageIndex
     * @param $pageSize
     * @return \think\Paginator
     */
    public function getInformationPaging($pageIndex, $pageSize)
    {
        $result = Db::name('information_hot')
            ->alias('ih')
            ->join('information_category ic', 'ih.categoryId = ic.id', 'left')
            ->where('ih.is_delete','eq',0)
            ->field('ih.id,ih.author,ih.categoryId,ic.name as categoryName,
            ih.title,ih.content,ih.is_md,ih.create_by,ih.create_time,ih.update_by,ih.update_time')
            ->paginate($pageSize, false, ['page' => $pageIndex]);
        return $result;
    }

    /**
     * 根据id获取资讯
     * @param $informationId
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getInformationById($informationId)
    {
        $result = Db::name('information_hot')
            ->alias('ih')
            ->join('information_category ic', 'ih.categoryId = ic.id', 'left')
            ->where('ih.is_delete','eq',0)
            ->where('ih.id', 'eq', $informationId)
            ->field('ih.id,ih.author,ih.categoryId,ic.name as categoryName,
            ih.title,ih.content,ih.is_md,ih.create_by,ih.create_time,ih.update_by,ih.update_time')
            ->find();
        return $result;
    }

}