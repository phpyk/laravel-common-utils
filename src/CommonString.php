<?php
/**
 * Created by PhpStorm.
 * User: kk
 * Date: 2017/4/20
 * Time: 下午3:20
 */

namespace Phpyk\Utils;


class CommonString
{

    public static function uniqueKey($prefix=null, $level = 'short')
    {
        switch($level)
        {
            case 'middle':
                return md5(date('YmdHis', time()).(Encryption::hex(3)));
                break;
            case 'short':
                return uniqid($prefix);
                break;
            default:
                return uniqid($prefix);
                break;
        }
    }

    /**
     * 过滤html字符
     * @param $string
     * @return array|mixed|string
     */
    public static function filterHtmlChars($string) {
        if(empty($string)) return '';

        if(is_array($string)) {
            foreach($string as $key => $val) {
                $string[$key] = self::filterHtmlChars($val);
            }
        } else {
            $string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1',
                str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string));

            $string = htmlspecialchars_decode($string);
            $string = stripslashes($string);
        }
        return $string;
    }

    public static function createToken()
    {
        $charid = md5(uniqid(mt_rand(), true));
        $uuid = substr($charid, 0, 2) .
            substr($charid, 8, 1) .
            substr($charid, 12, 2) .
            substr($charid, 16, 2) .
            substr($charid, 20, 3);
        return $uuid;
    }

    /**
     * 过滤任何非主流字符
     * @param type $strParam
     * @return type
     */
    public static function filterSpecialChar($strParam) {
        $regex = "/[^\x{4e00}-\x{9fa5}0-9a-zA-Z|\【|\】|\-|\,|\.|\，|\:|\。\！\→\‘\’\/\?\=\#]/iu";
        return preg_replace($regex, "", $strParam);
    }

}