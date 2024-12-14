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

namespace app\mc\utils;

use think\Response;

/**
 * Json输出类
 * Class Json
 * @package app\mc\utils
 */
class Json
{
    private $code = 200;

    public function code(int $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function make(int $status, string $msg, ?array $data = null, ?array $replace = []): Response
    {
        $res = compact('status', 'msg');

        if (!is_null($data))
            $res['data'] = $data;

        if (is_numeric($res['msg'])) {
            $res['code'] = $res['msg'];
            $res['msg'] = getLang($res['msg'], $replace);
        }


        return Response::create($res, 'json', $this->code);
    }

    public function success($msg = 'success', ?array $data = null, ?array $replace = []): Response
    {
        if (is_array($msg)) {
            $data = $msg;
            $msg = 'success';
        }

        return $this->make(200, $msg, $data, $replace);
    }

    public function fail($msg = 'fail', ?array $data = null, ?array $replace = []): Response
    {
        if (is_array($msg)) {
            $data = $msg;
            $msg = 'fail';
        }

        return $this->make(400, $msg, $data, $replace);
    }

    public function error($code = 400, $msg = 'fail', ?array $data = null, ?array $replace = []): Response
    {
        if (is_array($msg)) {
            $data = $msg;
            $msg = 'fail';
        }

        return $this->make($code, $msg, $data, $replace);
    }

    public function status($status, $msg, $result = [])
    {
        $status = strtoupper($status);
        if (is_array($msg)) {
            $result = $msg;
            $msg = 'success';
        }
        return $this->success($msg, compact('status', 'result'));
    }
}
