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

namespace app\mc\services\copyproduct\storage;

use app\mc\services\copyproduct\BaseCopyProduct;


/**
 * Class Copy
 * @package app\mc\services\product\storage
 */
class Copy extends BaseCopyProduct
{

    /**
     * 是否开通
     */
    const PRODUCT_OPEN = 'v2/copy/open';
    /**
     * 获取详情
     */
    const PRODUCT_GOODS = 'v2/copy/goods';

    /** 初始化
     * @param array $config
     */
    protected function initialize(array $config = [])
    {
        parent::initialize($config);
    }

    /** 是否开通复制
     * @return mixed
     */
    public function open()
    {
        return $this->accessToken->httpRequest(self::PRODUCT_OPEN, []);
    }

    /** 复制商品
     * @param string $url
     * @param array $options
     * @param string $yihaotongCopyAppid
     * @return mixed
     */
    public function goods(string $url, array $options = [])
    {
        $param['url'] = $url;
        return $this->accessToken->httpRequest(self::PRODUCT_GOODS, $param, 'post');
    }


}
