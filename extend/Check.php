<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/31 0031
 * Time: 17:06
 */

class Check
{
    /**
     * 所有的用户传进来的字符串都需要处理
     * @param $param 传进来的参数
     * @param $minLen  字符串的最短长度
     * @param $maxLen  字符串的最大长度
     * @return string 经过html预处理和mysql预处理的字符串
     */
    public static function checkStr($param, $minLen = 0, $maxLen = -1)
    {
        if ($param === null) {
            return null;
        }
        $len = mb_strlen($param, 'utf-8');
        if ($maxLen != -1) {
            //检查字符串的长度
            if ($len > $maxLen) {
                Util::printResult($GLOBALS['ERROR_INPUT_FORMAT'], "输入长度不正确");
                exit;
            }
        }
        if ($len < $minLen) {
            Util::printResult($GLOBALS['ERROR_INPUT_FORMAT'], "输入长度不正确");
            exit;
        }
        $param = htmlspecialchars(addslashes($param));
        return $param;
    }
    /**
     * 检查性别
     * @param $gender 性别
     * @param $isAssert 如果不符合是否直接终止程序运行，默认是true
     * @return 性别或者false
     */
    public static function checkGender($gender, $isAssert = true)
    {
        if ($gender == $GLOBALS['GENDER_FEMALE'] || $gender == $GLOBALS['GENDER_MALE'] || $gender == $GLOBALS['GENDER_UNKNOWN']) {
            return $gender;
        } else {
            if ($isAssert) {
                Util::printResult($GLOBALS['ERROR_INPUT_FORMAT'], "性别错误");
                exit;
            } else {
                return false;
            }
        }
    }
    /**
     * 检查日期格式,只支持xxxx-xx-xx
     * @param $str 传入的字符串
     * @param $isAssert 如果不符合是否直接终止程序运行，默认是true
     * @return 日期或者false
     */
    public static function checkDate($str, $isAssert = true)
    {
        $regex = "@^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$@AD";      //@是定界符，php的正则是支持在正则表达式后输入模式的,类似与2012-00-00这种输入是错误的
        $min = 10;
        $max = 10;
        $msg = "日期格式错误，请重新输入";
        return self::checkInput($str, $regex, $min, $max, $msg, $isAssert);
    }
    /**
     * 检查日期时间格式,只支持xxxx-xx-xx xx:xx:xx
     * @param $str 传入的字符串
     * @param $isAssert 如果不符合是否直接终止程序运行，默认是true
     * @return 日期或者false
     */
    public static function checkDateTime($str, $isAssert = true)
    {
        $regex = "@^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])\s([0-1][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$@AD";
        $min = 19;
        $max = 19;
        $msg = "日期格式错误，请重新输入";
        return self::checkInput($str, $regex, $min, $max, $msg, $isAssert);
    }
    /**
     * 检查邮箱格式
     * @param $email 邮箱地址
     * @param $isAssert 是否进行断言，true的话条件出错会直接终止程序运行，默认是true
     * @return mix 原样返回或者返回false
     */
    public static function checkEmail($email, $isAssert = true)
    {
        $regex = "|^[a-zA-Z0-9_\-.]+@([a-zA-Z0-9\-]+\.)+[a-zA-Z]+$|AD";
        $min = 5;
        $max = 50;
        $msg = "邮箱格式错误，请检查后重新填入";
        return self::checkInput(self::checkStr($email), $regex, $min, $max, $msg, $isAssert);
    }
    /**
     * 检查用户名是否合法,用户名中不能是数字开头，只能包含字母数字下划线
     * @param $username 用户名
     * @param $isAssert 是否进行断言，true的话条件出错会直接终止程序运行，默认是true
     * @return mix 原样返回或者返回false
     */
    public static function checkUserName($username, $isAssert = true)
    {
        $regex = "/^[^0-9]((?!@)\S)*$/AD";
        $min = 1;
        $max = 45;
        $msg = "用户名不合法，请检查后重新填入";
        return self::checkInput(self::checkStr($username), $regex, $min, $max, $msg, $isAssert);
    }
    /**
     * 检查电话号码,目前只考虑了国内的电话号码长度
     * @param $phoneNumber 电话号码
     * @param $isAssert 是否进行断言，true的话条件出错会直接终止程序运行，默认是true
     * @return mix 原样返回或者返回false
     */
    public static function checkPhoneNumber($phoneNumber, $isAssert = true)
    {
        $regex = "@^\d{8,15}$@AD";
        $min = 11;
        $max = 11;
        $msg = "手机号码格式错误，请检查后重新填入";
        return self::checkInput(self::checkStr($phoneNumber), $regex, $min, $max, $msg, $isAssert);
    }
    /**
     * 检查是否全部为数字
     * @param $numberStr 数字串
     * @param $isAssert 是否进行断言，true的话条件出错会直接终止程序运行，默认是true
     * @return mix 原样返回或者返回false
     */
    public static function checkNumber($numberStr, $isAssert = true)
    {
        $regex = "@^\d{8,15}$@AD";
        $min = 7;
        $max = 16;
        $msg = "号码格式错误，请不要输入非数字";
        return self::checkInput(self::checkStr($numberStr), $regex, $min, $max, $msg, $isAssert);
    }
    /**
     * 检查输入是否是整型
     * @param $input 输入
     * @param $isAssert 是否进行断言，true的话条件出错会直接终止程序运行，默认是true
     * @return mix 原样返回,如果是浮点数则会被转换成整数返回或者返回false
     */
    public static function checkInteger($input, $isAssert = true)
    {
        if (!is_numeric($input) || (intval($input) != floatval($input))) {
            if ($isAssert) {
                Util::printResult($GLOBALS['ERROR_INPUT_FORMAT'], "不是整型输入");
                exit;
            } else {
                return false;
            }
        }
        return $input;
    }
    public static function checkIntegerUnsigned($input, $isAssert = true)
    {
        if (!is_numeric($input) || (intval($input) != floatval($input))) {
            if ($isAssert) {
                Util::printResult($GLOBALS['ERROR_INPUT_FORMAT'], "不是整型输入");
                exit;
            } else {
                return false;
            }
        } else {
            if ($input <= 0) {
                Util::printResult($GLOBALS['ERROR_INPUT_FORMAT'], "格式错误");
                exit;
            } else {
                return $input;
            }
        }
    }
    /**
     * 检查输入是否是数组
     * @param $input 输入
     * @param $isAssert 是否进行断言，true的话条件出错会直接终止程序运行，默认是true
     * @return mix 原样返回或者返回false
     */
    public static function checkArray($input, $isAssert = true)
    {
        if (!is_array($input)) {
            if ($isAssert) {
                Util::printResult($GLOBALS['ERROR_INPUT_FORMAT'], "不是数组输入");
                exit;
            } else {
                return false;
            }
        }
        return $input;
    }
    /**
     * 检查输入是否是boolean
     * @param $input 输入
     * @param $isAssert 是否进行断言，true的话条件出错会直接终止程序运行，默认是true
     * @return mix 原样返回或者返回false
     */
    public static function checkBool($input, $isAssert = true)
    {
        if (!is_bool($input)) {
            if ($isAssert) {
                Util::printResult($GLOBALS['ERROR_INPUT_FORMAT'], "不是bool输入");
                exit;
            } else {
                return false;
            }
        }
        return $input;
    }
    /**
     * 检查模块是否存在
     * @param $input 模块id
     * @param $isAssert 是否进行断言，true的话条件出错会直接终止程序运行，默认是true
     * @return mix 原样返回或者返回false
     */
    public static function checkModule($input, $isAssert = true)
    {
        if ($input < -1 || $input > $GLOBALS['MAX_MODULE'] || !is_numeric($input)) {
            if ($isAssert) {
                Util::printResult($GLOBALS['ERROR_INPUT_FORMAT'], "不存在该模块");
                exit;
            } else {
                return false;
            }
        }
        return $input;
    }
    /**
     * 检查字符串代表的数组格式是否正确,比如   1,1,1,1,1,1,1,1
     * @param $input 字符串
     * @param $isAssert 是否进行断言，true的话条件出错会直接终止程序运行，默认是true
     * @return mix 原样返回或者返回false
     */
    public static function checkIdArray($input, $isAssert = true)
    {
        $regex = "@^(\d+,)*\d+$@";
        $min = 1;
        $max = -1;
        $msg = "消息数组格式不正确，请确认后重试";
        return self::checkInput($input, $regex, $min, $max, $msg, $isAssert);
    }
    /**
     * 检查url是否合法
     * @param $url url地址
     * @return boolean true代表合法,false代表不合法
     */
    public static function checkUrl($url, $isAssert = true)
    {
        $regex = "@^([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,6}$@";
        $min = 3;
        $max = 255;
        $msg = "url地址不合法";
        return self::checkInput($url, $regex, $min, $max, $msg, $isAssert);
    }
    /**
     * 检查颜色16进制格式是否正确
     * @param $color
     * @param bool $isAssert
     * @return mix
     */
    public static function checkColor($color, $isAssert = true)
    {
        $regex = "@^#[0-9a-fA-F]{6}$@";
        $min = 7;
        $max = 7;
        $msg = "颜色格式不对";
        return self::checkInput($color, $regex, $min, $max, $msg, $isAssert);
    }
    /**
     * 检查浮点类型是否符合格式
     * @param $input
     * @param $min 最小值
     * @param $max 最大值
     * @param bool $isAssert
     * @return bool
     */
    public static function checkFloat($input, $min, $max, $isAssert = true)
    {
        $regex = "@^[-+]?[0-9]*\.?[0-9]+$@";
        if (preg_match($regex, $input) == 0) {
            if ($isAssert) {
                Util::printResult($GLOBALS['ERROR_INPUT_FORMAT'], "不是浮点类型输入");
                exit;
            } else {
                return false;
            }
        } else {
            if (floatval($input) < $min || floatval($input) > $max) {
                if ($isAssert) {
                    Util::printResult($GLOBALS['ERROR_INPUT_FORMAT'], "经纬度不在取值范围内");
                    exit;
                } else {
                    return false;
                }
            } else {
                return $input;
            }
        }
    }
    /**
     * 检查url是否属于合法域名(这里会预填合法的域名),传进来的值要么是www.izuqun.com直接是域名，要么是符合$url = 'http://username:password@hostname/path?arg=value#anchor';
     * @param $url url地址
     * @return boolean true代表合法，false代表不合法
     */
    public static function checkUrlWithLimit($url)
    {
        $validUrl = Util::getValidDomain($url);
        if ($validUrl == null) {
            $validUrl = $url;
        }
        $urls = $GLOBALS['VALID_URL'];
        if (in_array($validUrl, $urls)) {
            return $url;
        }
        return false;
    }

    /**
     * 通用的输入检测函数
     * @param $input 输入数据
     * @param $regex 检测正则
     * @param $min 最小输入长度
     * @param $max 最大输入长度,-1代表不限制
     * @param $msg 出错信息
     * @param $isAssert 是否进行断言，true的话条件出错会直接终止程序运行，默认是true
     * @return mix 原样返回输入内容
     */
    public static function checkInput($input, $regex, $min, $max, $msg, $isAssert = true)
    {
        $len = mb_strlen($input);
        //先检查长度
        if (($len > $max && $max != -1) || $len < $min) {
            if ($isAssert) {
                Util::printResult($GLOBALS['ERROR_INPUT_FORMAT'], $msg);
                exit;
            } else {
                return false;
            }
        }
        if (preg_match($regex, $input) == 0) {
            if ($isAssert) {
                Util::printResult($GLOBALS['ERROR_INPUT_FORMAT'], $msg);
                exit;
            } else {
                return false;
            }
        }
        return $input;
    }
}