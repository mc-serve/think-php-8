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

namespace app\admin\validate\serve;

use think\Validate;


class ExpressValidata extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'com' => 'require',
        'temp_id' => 'require',
        'to_name' => 'require',
        'to_tel' => 'require|mobile',
        'to_address' => 'require',
        'siid' => 'require',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'com.require' => '400007',
        'temp_id.number' => '400360',
        'to_name.require' => '400008',
        'to_tel.require' => '400009',
        'to_tel.mobile' => '400010',
        'to_address.require' => '400011',
        'siid.require' => '400012',
    ];
}
