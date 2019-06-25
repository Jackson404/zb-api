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
            ->join('zb_news_category nc', 'n.categoryId = nc.id')
            ->where('n.isDelete', '=', 0)
            ->where('n.isShow', '=', 1)
            ->field('n.id,n.categoryId,nc.name as categoryName,n.title,n.keywords,n.description,n.content,n.imgUrl,n.createTime,n.createBy,n.updateTime,n.updateBy')
            ->order('n.id','desc')
            ->paginate(null, false, $config);
    }

    public function getDetail($newsId)
    {
        return $this->alias('n')
            ->join('zb_news_category nc', 'n.categoryId = nc.id')
            ->where('n.isDelete', '=', 0)
            ->where('n.id', '=', $newsId)
            ->field('n.id,n.categoryId,nc.name as categoryName,n.title,n.keywords,n.description,n.content,n.imgUrl,n.createTime,n.createBy,n.updateTime,n.updateBy')
            ->find();
    }

    public function getRandomNewsListLimit($categoryId,$newsId)
    {

        return $this->query("SELECT * FROM zb_news WHERE categoryId='$categoryId'
             AND isDelete=0 AND isShow=1 AND id <> '$newsId'
             ORDER BY rand() LIMIT 0,5");

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
            ->order('id','desc')
            ->paginate(null, false, $config);
    }

    public function getIndexPageNews()
    {
        return $this->where('isDelete', '=', 0)
            ->where('categoryId', '=', 2)
            ->where('isShow', '=', 1)
            ->order('id','desc')
            ->limit(0, 6)->select();
    }
}