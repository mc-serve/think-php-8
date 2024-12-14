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

namespace app\mc\services\sms;

use app\mc\basic\BaseStorage;
use app\mc\services\AccessTokenServeService;

/**
 * Class BaseSmss
 * @package app\mc\basic
 */
abstract class BaseSms extends BaseStorage
{

    /**
     * access_token
     * @var null
     */
    protected $accessToken = NULL;

    /**
     * BaseSmss constructor.
     * @param string $name
     * @param AccessTokenServeService $accessTokenServeService
     * @param string $configFile
     */
    public function __construct(string $name, AccessTokenServeService $accessTokenServeService, string $configFile, array $config = [])
    {
        $this->accessToken = $accessTokenServeService;
        $this->name = $name;
        $this->configFile = $configFile;
        $this->initialize($config);
    }

    /**
     * 初始化
     * @param array $config
     * @return mixed|void
     */
    protected function initialize(array $config = [])
    {

    }


    /**
     * 开通服务
     * @return mixed
     */
    abstract public function open();

    /**修改签名
     * @return mixed
     */
    abstract public function modify(string $sign = null, string $phone, string $code);

    /**用户信息
     * @return mixed
     */
    abstract public function info();

    /**发送短信
     * @return mixed
     */
    abstract public function send(string $phone, string $templateId, array $data);

    /**
     * 短信模板
     * @param int $page
     * @param int $limit
     * @param int $type
     * @return mixed
     */
    abstract public function temps(int $page, int $limit, int $type);


    /**
     * 申请模板
     * @param string $title
     * @param string $content
     * @param int $type
     * @return mixed
     */
    abstract public function apply(string $title, string $content, int $type);

    /**
     * 模板记录
     * @param int $tempType
     * @param int $page
     * @param int $limit
     * @return mixed
     */
    abstract public function applys(int $tempType, int $page, int $limit);

    /**发送记录
     * @return mixed
     */
    abstract public function record($record_id);
}
