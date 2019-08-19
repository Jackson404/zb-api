<?php

namespace app\api\controller\v1\admin;

use app\api\model\EpMsgModel;
use think\Request;
use Util\Check;
use Util\Util;

class EpMsg extends AdminBase
{
    public function add()
    {
        $params = Request::instance()->param();
        $title = Check::check($params['title'] ?? '');
        $content = Check::check($params['content'] ?? '');
        $recUserId = Check::checkInteger($params['recUserId']);

        $sendUserId = $GLOBALS['userId'];

        $data = [
            'sendUserId' => $sendUserId,
            'title' => $title,
            'content' => $content,
            'recUserId' => $recUserId,
            'createTime' => currentTime(),
            'updateTime' => currentTime()
        ];

        $epMsgModel = new EpMsgModel();
        $insertRow = $epMsgModel->save($data);

        if ($insertRow > 0) {
            $data['insertRow'] = $insertRow;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '添加失败');
            exit;
        }

    }
}