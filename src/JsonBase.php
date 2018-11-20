<?php
namespace Phpyk\Utils;


use Illuminate\Http\JsonResponse;

class JsonBase
{
    const SUCCESS_CODE = 200;
    const FAILURE_CODE = 500;
    public static function renderJsonWithSuccess($data = [], $bizMsg = 'ok', $returnStatus = self::SUCCESS_CODE, $bizAction = 0)
    {
        return self::renderJsonBase($data, $bizMsg, $returnStatus, $bizAction);
    }

    public static function renderJsonWithFail($bizMsg , $data = [], $returnStatus = self::FAILURE_CODE,$bizAction = 1)
    {
        return self::renderJsonBase($data, $bizMsg, $returnStatus, $bizAction);
    }

    public static function renderJsonBase($data, $bizMsg, $returnStatus, $bizAction)
    {

        return new JsonResponse([
            'biz_action'    => $bizAction,
            'biz_msg'       => $bizMsg,
            'return_status' => $returnStatus,
            'server_time'   => time(),
            'data'          => (object)$data
        ]);
    }
}
