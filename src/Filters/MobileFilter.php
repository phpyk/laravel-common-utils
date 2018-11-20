<?php
namespace Phpyk\Utils\Filters;
/**
 * Created by PhpStorm.
 * User: kk
 * Date: 2016/12/22
 * Time: 下午3:57
 */



class MobileFilter
{
    const CHINA = '0086';
    const MALAYSIA = '0060';
    const PHILIPPINES = '0063';
    const JAPAN = '0081';
    const HONGKONG = '00852';

    private static $_types = array(
        self::CHINA => '中国大陆',
        self::MALAYSIA => '马来西亚',
        self::PHILIPPINES => '菲律宾',
        self::JAPAN => '日本',
        self::HONGKONG => '香港',
    );

    const SHORT_NAME_TO_CODE_ENUM = [
        'CN' => self::CHINA,
        'MY' => self::MALAYSIA,
    ];

    public static function checkMobile($countryCode, $mobile)
    {
        if ($countryCode == self::CHINA) {
            if (preg_match("/^1[34578]\d{9}$/", $mobile)) {
                return true;
            }
        } elseif ($countryCode == self::MALAYSIA) {
            //马来西亚的手机号码，前面都是0的，(有011,012，014，016，017和019)。
            //这代表着不同的电讯公司，012是明讯，016是数码电讯，019是天地通，011
            //前面的0可以省略 0127834545
            if (preg_match("/^01[124679]\d{7}$/", $mobile) || preg_match("/^1[124679]\d{7}$/", $mobile)) {
                return true;
            }
        } elseif ($countryCode == self::PHILIPPINES) {
            //菲律宾手机号以9开头，不加0长度为10位，前面的0可以省略
            //eg：916-550-9626
            if (preg_match("/^09\d{9}$/", $mobile) || preg_match("/^9\d{9}$/", $mobile)) {
                return true;
            }
        } elseif ($countryCode == self::JAPAN) {
            //日本的电话号码由三个部分组成[06(区号)＋5768(局号)＋6578(受话人号码)]
            //日本手机号码11位，有080开有和090开头的，
            //如果从中国打，要把前面的0去掉，比如打日本手机09012345678，要拨0081-9012345678
            if (preg_match("/^08\d{9}$/", $mobile) || preg_match("/^09\d{9}$/", $mobile) || preg_match("/^8\d{9}$/", $mobile) || preg_match("/^9\d{9}$/", $mobile)) {
                return true;
            }
        } elseif ($countryCode == self::HONGKONG) {
            //香港的电话号码由9或6开头 后面加7位数字
            if (preg_match("/^([6|9])\d{7}$/", $mobile)) {
                return true;
            }
        }

        return false;
    }
}