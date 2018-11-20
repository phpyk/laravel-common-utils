<?php

namespace Phpyk\Utils;

class Encryption
{
    public static function encryptPwd($pw)
    {
        return md5(md5($pw));
    }

    /*
     * random_bytes 生成 伪随机数字节
     * bin2hex($str) 返回 $str 16进制表示的ASCII字符串
     * eg: Encryption::hex(3) #=> "92deac"
     * eg: Encryption::hex(3) #=> "b5b311"
     * ...
     */
    public static function hex($num)
    {
        return bin2hex(random_bytes($num));
    }

}