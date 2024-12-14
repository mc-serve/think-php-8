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

class LiveAnchorValidate extends Validate
{

    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'name' => 'require',
        'wechat' => 'require',
        'phone' => 'require|checkPhone',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'name.require' => '400107',
        'wechat.require' => '400110',
        'phone.require' => '400333',
        'phone.checkPhone' => '400252',
    ];

    protected function checkPhone($value): bool
    {
        return check_phone($value) == true;
    }

    protected $scene = [
        'save' => ['name', 'wechat', 'phone'],
    ];
}
