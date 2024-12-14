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

namespace app\mc\services\copyproduct;

use app\mc\services\AccessTokenServeService;
use app\mc\basic\BaseStorage;

/**
 * Class BaseProduct
 * @package app\mc\services\copyproduct
 */
abstract class BaseCopyProduct extends BaseStorage
{

    /**
     * access_token
     * @var null
     */
    protected $accessToken = NULL;

    /**
     * BaseProduct constructor.
     * @param string $name
     * @param AccessTokenServeService $accessTokenServeService
     * @param string $configFile
     */
    public function __construct(string $name, AccessTokenServeService $accessTokenServeService, string $configFile)
    {
        parent::__construct($name, [], $configFile);
        $this->accessToken = $accessTokenServeService;
    }

    /**
     * 初始化
     * @param array $config
     * @return mixed|void
     */
    protected function initialize(array $config = [])
    {
//        parent::initialize($config);
    }

    /**
     * 开通服务
     * @return mixed
     */
    abstract public function open();

    /**复制商品
     * @param string $url
     * @param array $options
     * @return mixed
     */
    abstract public function goods(string $url, array $options = []);
}
