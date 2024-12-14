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
namespace app\admin\validate\marketing;

use think\Validate;

class StoreCouponValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'title' => 'require',
        'image' => 'require',
        'store_category_id' => 'require',
        'coupon_price' => 'require',
        'use_min_price' => 'require',
        'coupon_time' => 'require',
        'status' => 'In:0,1',
        'type' => ['require', 'In:0,1,2'],
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'title.require' => '400372',
        'image.require' => '400337',
        'store_category_id.require' => '400373',
        'coupon_price.require' => '400374',
        'use_min_price.require' => '400375',
        'coupon_time.require' => '400376',
    ];

    protected $scene = [
        'save' => ['title', 'coupon_price', 'use_min_price', 'coupon_time'],
        'type' => ['title', 'store_category_id', 'coupon_price', 'use_min_price', 'coupon_time'],
        'product' => ['title', 'image', 'coupon_price', 'use_min_price', 'coupon_time'],
    ];
}
