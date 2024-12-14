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

namespace app\mc\basic;

use app\mc\traits\ModelTrait;
use think\db\Query;
use think\Model;

/**
 * Class BaseModel
 * @package app\mc\basic
 * @mixin ModelTrait
 * @mixin Query
 */
class BaseModel extends Model
{

}
