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
            ->field('n.id,n.categoryId,nc.name as categoryName,n.title,n.keywords,n.description,n.content,n.imgUrl,n.createTime,n.createBy,n.updateTime,n.updateBy')
            ->paginate(null, false, $config);
    }

}