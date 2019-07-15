<?php

namespace app\api\model;

use think\Model;

class NewsModel extends Model
{
    protected $name = 'news';
    protected $pk = 'id';
    protected $resultSetType = 'collection';

    public function edit($newsId, $data)
    {
        return $this->where('isDelete', '=', 0)->where('id', '=', $newsId)->update($data);
    }

    public function getByPage($pageIndex, $pageSize)
    {
        $config = [
            'list_rows' => $pageSize,
            'page' => $pageIndex
        ];
        return $this->alias('n')
            ->join('zb_news_category nc', 'n.categoryId = nc.id', 'left')
            ->where('n.isDelete', '=', 0)
            ->where('n.isShow', '=', 1)
            ->field('n.id,n.categoryId,nc.name as categoryName,n.title,n.keywords,n.description,n.content,n.imgUrl,n.isShow,n.createTime,n.createBy,n.updateTime,n.updateBy')
            ->order('n.id', 'desc')
            ->paginate(null, false, $config);
    }

    public function getByPageWithAdmin($pageIndex, $pageSize)
    {
        $config = [
            'list_rows' => $pageSize,
            'page' => $pageIndex
        ];
        return $this->alias('n')
            ->join('zb_news_category nc', 'n.categoryId = nc.id', 'left')
            ->where('n.isDelete', '=', 0)
            ->field('n.id,n.categoryId,nc.name as categoryName,n.title,n.keywords,n.description,n.content,n.imgUrl,n.isShow,n.createTime,n.createBy,n.updateTime,n.updateBy')
            ->order('n.id', 'desc')
            ->paginate(null, false, $config);
    }


    public function getDetail($newsId)
    {
        return $this->alias('n')
            ->join('zb_news_category nc', 'n.categoryId = nc.id', 'left')
            ->where('n.isDelete', '=', 0)
            ->where('n.id', '=', $newsId)
            ->field('n.id,n.categoryId,nc.name as categoryName,n.title,n.keywords,n.description,n.content,n.imgUrl,n.isShow,n.createTime,n.createBy,n.updateTime,n.updateBy')
            ->find();
    }

    public function getRandomNewsListLimit($categoryId, $newsId)
    {
        $sql = "SELECT n.id,n.categoryId,nc.name as categoryName,n.title,n.keywords,n.description,n.content,n.imgUrl,n.createTime,n.createBy,n.updateTime,n.updateBy FROM zb_news as n LEFT JOIN zb_news_category as nc 
            ON n.categoryId = nc.id
            WHERE n.categoryId='$categoryId'
            AND n.isDelete=0 AND n.isShow=1 AND n.id <> '$newsId'
            ORDER BY rand() LIMIT 0,5";

        return $this->query($sql);

    }

    public function getRandomNextNewsLimit($newsId)
    {
        $sql = "SELECT n.id,n.categoryId,nc.name as categoryName,n.title,n.keywords,n.description,n.content,n.imgUrl,n.createTime,n.createBy,n.updateTime,n.updateBy FROM zb_news as n LEFT JOIN zb_news_category as nc 
            ON n.categoryId = nc.id
            AND n.isDelete=0 AND n.isShow=1 AND n.id <> '$newsId'
            ORDER BY rand() LIMIT 0,1";
        return $this->query($sql);
    }

    public function getNewsByCateIdPage($categoryId, $pageIndex, $pageSize)
    {
        $config = [
            'list_rows' => $pageSize,
            'page' => $pageIndex
        ];
        return $this->where('categoryId', '=', $categoryId)
            ->where('isShow', '=', 1)
            ->where('isDelete', '=', 0)
            ->order('id', 'desc')
            ->paginate(null, false, $config);
    }

    public function getNewsByCateIdPageAdmin($categoryId, $pageIndex, $pageSize)
    {
        $config = [
            'list_rows' => $pageSize,
            'page' => $pageIndex
        ];

        return $this->alias('n')
            ->join('zb_news_category nc', 'n.categoryId = nc.id', 'left')
            ->where('n.isDelete', '=', 0)
            ->where('n.categoryId', '=', $categoryId)
            ->field('n.id,n.categoryId,nc.name as categoryName,n.title,n.keywords,n.description,n.content,n.imgUrl,n.isShow,n.createTime,n.createBy,n.updateTime,n.updateBy')
            ->order('n.id', 'desc')
            ->paginate(null, false, $config);
    }


    public function getIndexPageNewsByCateId($cateId, $limit)
    {
        return $this->where('isDelete', '=', 0)
            ->where('categoryId', '=', $cateId)
            ->where('isShow', '=', 1)
            ->order('id', 'desc')
            ->limit(0, $limit)->select();
    }


}