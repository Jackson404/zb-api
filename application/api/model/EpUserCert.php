<?php
namespace  app\api\model;
use think\Model;

class  EpUserCert extends Model{
    protected $table = 'zb_enterprise_cert';
    protected $pk = 'id';
    protected $resultSetType = 'collection';
}