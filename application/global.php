<?php
/**
 * Created by PhpStorm.
 * User: xuliulei
 * Date: 2017/12/14 0014
 * Time: 16:08
 */

/*********************以下是全局变量的定义****************************/

/**
 * 错误码定义
 * 0代表没有错误
 * -10000开头是SQL错误
 * -20000开头是操作错误
 * -30000开头的是异常错误
 */
$GLOBALS['ERROR_SUCCESS'] = "0";
$GLOBALS['ERROR_SQL_INIT'] = "-10000001";                   //SQL数据库初始化错误
$GLOBALS['ERROR_SQL_INSERT'] = "-10000002";                 //插入出错，没有任何记录插入
$GLOBALS['ERROR_SQL_DELETE'] = "-10000003";                 //删除出错，没有任何记录被删除
$GLOBALS['ERROR_SQL_UPDATE'] = "-10000004";                 //更新出错，没有任何记录被更新
$GLOBALS['ERROR_SQL_QUERY'] = "-10000005";                    //查询出错，记录不存在
$GLOBALS['ERROR_POST'] = "-10000006";                        //不是POST传值
$GLOBALS['ERROR_RECORD_INSERT'] = "-10000007";                     //没有需要添加进数据库的新日志记录
$GLOBALS['ERROR_REDISKEY_NOT_EXISTS'] = "-10000008";                //查询出错，不存在要查询的键
$GLOBALS['ERROR_SERVERID_EXISTS'] = "-10000009";                //服务器Id不存在

$GLOBALS['ERROR_LOGIN'] = "-20000001";                        //登录错误
$GLOBALS['ERROR_PARAM_MISSING'] = "-20000002";                //参数缺失
$GLOBALS['ERROR_PERMISSION'] = "-20000003";                    //权限错误
$GLOBALS['ERROR_REQUEST'] = "-20000004";                    //请求错误，接口不存在
$GLOBALS['ERROR_FAMILY_LOGIN'] = "-20000005";                //家族登录出错
$GLOBALS['ERROR_REGISTER_DUPLICATEEMAIL'] = "-20000006";    //注册邮箱重复出错
$GLOBALS['ERROR_REGISTER'] = "-20000007";                    //注册出错
$GLOBALS['ERROR_REGISTER_DUPLICATEUSERNAME'] = "-20000008";    //注册用户名重复出错
$GLOBALS['ERROR_REGISTER_DUPLICATEPHONE'] = "-20000009";    //注册手机重复出错
$GLOBALS['ERROR_REGISTER_TYPE'] = "-20000010";                //注册方式不存在出错
$GLOBALS['ERROR_LOGIN_TYPE'] = "-20000011";                    //登录方式不存在出错
$GLOBALS['ERROR_ZONE_EXIST'] = "-20000012";                    //空间已经存在，不允许重复创建
$GLOBALS['ERROR_INPUT_FORMAT'] = "-20000013";                //输入格式错误
$GLOBALS['ERROR_COLLECTION_EXIST'] = "-20000014";           //收藏已经存在，不允许重复创建
$GLOBALS['ERROR_REGISTER_DUPLICATEWECHAT'] = "-20000015";   //微信账号已绑定
$GLOBALS['ERROR_INSERT_REDIS'] = "-20000016";               //REDIS插入失败
$GLOBALS['ERROR_ACCOUNT_NOT_EXIST'] = "-20000017";               //REDIS插入失败
$GLOBALS['ERROR_PARAM_WRONG'] = "-20000018";                //参数不正确
$GLOBALS['ERROR_PHONE_USETIMES_LIMITED'] = "-20000019";     //电话在24小时中使用了10次
$GLOBALS['ERROR_USER_EXISTS'] = "-20000020";                //用户不存在
$GLOBALS['ERROR_PASSWORD'] = "-20000021";                   // 密码错误
$GLOBALS['ERROR_VERSION_INPUT'] = "-20000022";            //版本号小雨上次输入的
$GLOBALS['ERROR_SYSTEMUSER_EXISTS'] = "-20000023";                       //'系统用户已经存在'
$GLOBALS['ERROR_CHANNEL_EXISTS'] = "-20000024";    //'频道名字已经存在'
$GLOBALS['ERROR_SEND_FAILED'] = "-20000025";
$GLOBALS['ERROR_FAILED_VALIDATE'] = "-20000026";


$GLOBALS['ERROR_EXCEPTION'] = "-30000001";                    //出现异常
$GLOBALS['ERROR_DB_EXCEPTION'] = "-30000000";                //数据库操作出现异常
$GLOBALS['ERROR_PDO_EXCEPTION'] = "-300000006";            //PDO操作出现异常
$GLOBALS['ERROR_DATA_NOT_FOUND_EXCEPTION'] = "-300000007";    //数据操作出现异常
$GLOBALS['ERROR_MODEL_NOT_FOUND_EXCEPTION'] = "-300000008";    //模型操作出现异常

$GLOBALS['ERROR_FILE_UPLOAD'] = "-30000002";                //文件上传失败
$GLOBALS['ERROR_VCODE'] = "-30000003";                      //验证错误
$GLOBALS['ERROR_WECHAT_ACCESS_TOKEN'] = "-30000004";        //获取微信access_token错误
$GLOBALS['ERROR_WECHAT_USERINFO'] = "-30000005";            //获取用户信息错误


$GLOBALS['GENDER_FEMALE'] = "0";        //女性
$GLOBALS['GENDER_MALE'] = "1";        //男性
$GLOBALS['GENDER_UNKNOWN'] = "2";     //未知


$GLOBALS['REGISTER_TYPE_EMAIL'] = "1";    //通过邮箱注册
$GLOBALS['REGISTER_TYPE_PHONE'] = "2";    //通过手机注册

$GLOBALS['LOGIN_TYPE_EMAIL'] = "1";        //通过邮件登录
$GLOBALS['LOGIN_TYPE_PHONE'] = "2";        //登录手机号登录
$GLOBALS['LOGIN_TYPE_USERNAME'] = "3";    //通过用户名登录

$GLOBALS['LOGIN_DEVICE_BROWSER'] = "1";        //浏览器方式登录
$GLOBALS['LOGIN_DEVICE_APP'] = "2";            //app方式登录
$GLOBALS['LOGIN_DEVICE_WECHAT'] = "3";        //微信方式登录

$GLOBALS['LOGIN_FAILED'] = "-1";                //登录失败的值
$GLOBALS['LOGIN_SUCCESS'] = "1";                //登录成功的值

$GLOBALS['LOCK_LOGIN_TIMES'] = "5";            //登录失败锁定的次数

$GLOBALS['SHA256_SALT'] = "adhisugdd";        //加密的salt

$GLOBALS['WRITE_MODE_ALONE'] = "0";            //单独撰写
$GLOBALS['WRITE_MODE_UNION'] = "1";            //联合撰写

$GLOBALS['userId'] = 0;
$GLOBALS['indexUserId'] = 0;

$GLOBALS['TOKEN_API'] = 'API';
$GLOBALS['REQUEST_ILLEGAL'] = '-1';

$GLOBALS['ACCESS_KEY_ID'] = 'LTAIfhJUYdW6Rd2t'; //短信
$GLOBALS['ACCESS_KEY_SECRET'] = 'e7CJAkAqUb9u4iOhFDnMUdEP18NX5E';

$GLOBALS['WEB_ID'] = 'zhengbuwangluokejiwebid';
$GLOBALS['WEB_SECRET'] = 'zhengbuwangluokejisecret';


/*************************************一些默认的设置********************************************/

$GLOBALS['TEST_MODE'] = true;       //测试模式,true的话需要邀请码才能注册,fasle的话不需要邀请码

/*************************************数据库参数********************************************/
$GLOBALS['redis_port'] = '';
$GLOBALS['redis_auth'] = '';
//$GLOBALS['CDN_URL'] = '47.103.102.22';
$GLOBALS['CDN_URL'] = '127.0.0.1:9090';
$GLOBALS['db_config'] = [
    // 数据库类型
    'type' => 'mysql',
    // 服务器地址
    'hostname' => '47.103.59.100',
    // 数据库名
    'database' => 'zb_soft_shop_cn',
    // 用户名
    'username' => 'root',
    // 密码
    'password' => '0045abcba22aad6d',
    // 端口
    'hostport' => '3306',
    // 连接dsn
    'dsn' => '',
    // 数据库连接参数
    'params' => [],
    // 数据库编码默认采用utf8
    'charset' => 'utf8mb4',
    // 数据库表前缀
    'prefix' => 'grace_',
    // 数据库调试模式
    'debug' => true,
    // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
    'deploy' => 0,
    // 数据库读写是否分离 主从式有效
    'rw_separate' => false,
    // 读写分离后 主服务器数量
    'master_num' => 1,
    // 指定从服务器序号
    'slave_no' => '',
    // 是否严格检查字段是否存在
    'fields_strict' => true,
    // 数据集返回类型
    'resultset_type' => 'array',
    // 自动写入时间戳字段
    'auto_timestamp' => false,
    // 时间字段取出后的默认时间格式
    'datetime_format' => 'Y-m-d H:i:s',
];