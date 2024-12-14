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
namespace app\admin\validate\setting;

use think\Validate;

class ShippingTemplatesValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'name' => 'require',
        'region_info' => 'array',
        'appoint_info' => 'array',
        'no_delivery_info' => 'array',
        'type' => 'number',
        'appoint' => 'number',
        'no_delivery' => 'number',
        'sort' => 'number'
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'name.require' => '400025',
        'region_info.array' => '400026',
        'appoint_info.array' => '400027',
        'no_delivery_info.array' => '400028',
        'type.number' => '400029',
        'appoint.number' => '400030',
        'no_delivery.number' => '400031',
        'sort.number' => '400032',
    ];

    protected $scene = [
        'save' => ['name', 'type', 'appoint', 'sort', 'region_info', 'appoint_info', 'no_delivery_info', 'no_delivery'],
    ];
}
