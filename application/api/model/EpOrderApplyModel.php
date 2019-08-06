<?php

namespace app\api\controller\v1;

use think\Model;

class EpOrderApplyModel extends Model
{
    protected $table = 'zb_enterprise_order_apply';
    protected $pk = 'id';
    protected $resultSetType = 'collection';

}