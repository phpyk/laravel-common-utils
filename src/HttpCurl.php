<?php
/**
 * Created by PhpStorm.
 * User: kk
 * Date: 2017/4/23
 * Time: 下午2:22
 */

namespace Phpyk\Utils;


class HttpCurl
{
    /**
     * 通过curl发送post请求
     * @param      $uri
     * @param      $postData
     * @param bool $postJson
     * @param int  $connecttimeout
     * @param int  $timeout
     * @return bool|mixed
     */
    public static function postCURL($uri, $postData, $postJson = false, $connecttimeout = 300, $timeout = 200)
    {
        if(empty($uri) || empty($postData)) return false;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_POST, true);
        if($postJson){
            $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json; charset=utf-8',
                    'Content-Length: '.strlen($postData))
            );
        }else{
            $postData = http_build_query($postData);
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $connecttimeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }


    /**
     * 通过curl发送get请求
     * @param     $uri
     * @param int $connecttimeout
     * @param int $timeout
     * @return bool|mixed
     */
    public static function getCURL($uri, $connecttimeout = 300, $timeout = 200)
    {
        if(empty($uri)) return false;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $connecttimeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }


}