<?php
/**
 * Created by PhpStorm.
 * User: kk
 * Date: 2016/12/22
 * Time: 下午3:20
 */

namespace Phpyk\Utils\Filters;


class PassportFilter
{
    /**
     * 检查护照号是否正确
     * @param  [type] $idNumber [description]
     * @return [type]           [description]
     */
    public static function checkPassport($idNumber)
    {   

        // 护照号码的格式：
        // 因私普通护照号码格式有: 14/15+7位数 G+8位数；
        // 因公普通的是: P+7位数；
        // 公务的是: S+7位数 或者 S+8位数 以D开头的是外交护照
        // E: 有电子芯片的普通护照为“E”字开头

        if (preg_match("/^(1[45][0-9]{7}|E[0-9]{8}|G[0-9]{8}|P[0-9]{7}|S[0-9]{7,8}|D[0-9]+)$/", $idNumber)) {
            return true;
        }
        return false;
    }
}