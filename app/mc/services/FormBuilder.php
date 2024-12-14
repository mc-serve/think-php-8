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

namespace app\mc\services;

//use FormBuilder\Factory\Iview as Form;
use FormBuilder\Factory\Elm as Form;

/**
 * Form Builder
 * Class FormBuilder
 * @package app\mc\services
 */
class FormBuilder extends Form
{

    public static function setOptions($call){
        if (is_array($call)) {
            return $call;
        }else{
            return  $call();
        }

    }


}
