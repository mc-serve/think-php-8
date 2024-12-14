<?php
// +----------------------------------------------------------------------
// | MC [ MC多应用系统，全产业链赋能 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2022~2025 https://www.mc-serve.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed MC并不是自由软件，未经许可不能去掉MC相关版权
// +----------------------------------------------------------------------
// | Author: MC Team <cs@mc-serve.com>
// +----------------------------------------------------------------------

namespace app\mc\services\express\storage;

use app\mc\services\express\BaseExpress;
use app\mc\services\HttpService;

/**
 * Class AliyunExpress
 * @package app\mc\services\express\storage
 */
class AliyunExpress extends BaseExpress
{
    /**
     * @var string[]
     */
    protected static $api = [
        'query' => 'https://wuliu.market.alicloudapi.com/kdi'
    ];

    /**
     * @param string $no
     * @param string $type
     * @return bool|mixed
     */
    public function query(string $no = '', string $type = '', string $appCode = '')
    {
        if (!$appCode) return false;
        $res = HttpService::getRequest(self::$api['query'], compact('no', 'type'), ['Authorization:APPCODE ' . $appCode]);
        return json_decode($res, true) ?: false;
    }

    public function open()
    {
        // TODO: Implement open() method.
    }

    public function dump($data)
    {
        // TODO: Implement dump() method.
    }

    public function temp(string $com)
    {
        // TODO: Implement temp() method.
    }
}
