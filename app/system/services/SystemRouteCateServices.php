<?php
/**
 *  +----------------------------------------------------------------------
 *  | MC [ MC多应用系统，全产业链赋能 ]
 *  +----------------------------------------------------------------------
 *  | Copyright (c) 2022~2025 https://www.mc-serve.com All rights reserved.
 *  +----------------------------------------------------------------------
 *  | Licensed MC并不是自由软件，未经许可不能去掉MC相关版权
 *  +----------------------------------------------------------------------
 *  | Author: MC Team <cs@mc-serve.com>
 *  +----------------------------------------------------------------------
 */

namespace app\system\services;


use app\system\dao\SystemRouteCateDao;
use app\mc\basic\BaseServices;
use app\mc\services\FormBuilder;

/**
 * Class SystemRouteCateServices
 * @author 等风来
 * @email 136327134@qq.com
 * @date 2023/4/6
 * @package app\system\services
 */
class SystemRouteCateServices extends BaseServices
{

    /**
     * SystemRouteCateServices constructor.
     * @param SystemRouteCateDao $dao
     */
    public function __construct(SystemRouteCateDao $dao)
    {
        $this->dao = $dao;
    }

    public function getPathValue(array $path)
    {
        $pathAttr = explode('/', $path);
        $pathData = [];
        foreach ($pathAttr as $item) {
            if (!$item) {
                $pathData[] = $item;
            }
        }
        return $pathAttr;
    }

    /**
     * @param array $path
     * @param int $id
     * @return string
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/6
     */
    public function setPathValue(array $path, int $id)
    {
        return ($path ? '/' . implode('/', $path) : '') . '/' . $id . '/';
    }

    /**
     * @param string $appName
     * @return array
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/6
     */
    public function getAllList(string $appName = 'outapi', string $field = '*', string $order = '')
    {
        $list = $this->dao->selectList(['app_name' => $appName], $field, 0, 0, $order)->toArray();
        return get_tree_children($list);
    }

    /**
     * @param int $id
     * @param string $appName
     * @return array
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/6
     */
    public function getFrom(int $id = 0, string $appName = 'outapi')
    {
        $url = '/system/route_cate';
        $cateInfo = [];
        $path = [];
        if ($id) {
            $cateInfo = $this->dao->get($id);
            $cateInfo = $cateInfo ? $cateInfo->toArray() : [];
            $url .= '/' . $id;
            $path = explode('/', $cateInfo['path']);
            $newPath = [];
            foreach ($path as $item) {
                if ($item) {
                    $newPath[] = $item;
                }
            }
            $path = $newPath;
        }
        $options = $this->dao->selectList(['app_name' => $appName], 'name as label,id as value,id,pid')->toArray();
        $rule = [
//            FormBuilder::cascader('path', '上级分类', $path)->data(get_tree_children($options)),
            FormBuilder::input('name', '分类名称', $cateInfo['name'] ?? '')->required(),
            FormBuilder::number('sort', '排序', (int)($cateInfo['sort'] ?? 0)),
            FormBuilder::hidden('app_name', $appName)
        ];

        return create_form($id ? '修改分类' : '添加分类', $rule, $url, $id ? 'PUT' : 'POST');
    }
}
