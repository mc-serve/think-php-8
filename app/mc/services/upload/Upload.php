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
namespace app\mc\services\upload;

use app\mc\basic\BaseManager;
use think\facade\Config;

/**
 * Class Upload
 * @package app\mc\services\upload
 * @mixin \app\mc\services\upload\storage\Local
 * @mixin \app\mc\services\upload\storage\OSS
 * @mixin \app\mc\services\upload\storage\COS
 * @mixin \app\mc\services\upload\storage\Qiniu
 * @mixin \app\mc\services\upload\storage\Jdoss
 * @mixin \app\mc\services\upload\storage\Tyoss
 */
class Upload extends BaseManager
{
    /**
     * 空间名
     * @var string
     */
    protected $namespace = '\\app\\mc\\services\\upload\\storage\\';

    /**
     * 设置默认上传类型
     * @return mixed
     */
    protected function getDefaultDriver()
    {
        return Config::get('upload.default', 'local');
    }


}
