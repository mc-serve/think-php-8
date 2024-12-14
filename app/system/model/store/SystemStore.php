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

namespace app\system\model\store;

use app\mc\basic\BaseModel;
use app\mc\traits\ModelTrait;
use think\Model;

/**
 * 门店列表
 * Class SystemStore
 * @package app\system\model\store
 */
class SystemStore extends BaseModel
{
    use ModelTrait;

    /**
     * 数据表主键
     * @var string
     */
    protected $pk = 'id';

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'system_store';

    /**
     * 经纬度获取器
     * @param $value
     * @param $data
     * @return string
     */
    public static function getLatlngAttr($value, $data)
    {
        return $data['latitude'] . ',' . $data['longitude'];
    }

    /**
     * 店铺类型搜索器
     * @param Model $query
     * @param $value
     */
    public function searchTypeAttr($query, $value)
    {
        switch ((int)$value) {
            case 1:
                $query->where(['is_del' => 0, 'is_show' => 0]);
                break;
            case 0:
                $query->where(['is_del' => 0, 'is_show' => 1]);
                break;
            default:
                $query->where('is_del', 1);
                break;
        }
    }

    /**
     * 手机号,id,昵称搜索器
     * @param Model $query
     * @param $value
     */
    public function searchKeywordsAttr($query, $value)
    {
        if ($value) {
            $query->where('id|name|introduction|phone', 'LIKE', "%$value%");
        }
    }
}
