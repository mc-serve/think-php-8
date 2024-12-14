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

namespace app\system\services\config;

use app\system\dao\config\SystemGroupDataDao;
use app\mc\basic\BaseServices;
use app\mc\exceptions\AdminException;
use app\mc\services\FormBuilder as Form;

/**
 * 组合数据数据集
 * Class SystemGroupDataServices
 * @package app\system\services\config
 * @method delete($id, ?string $key = null) 删除数据
 * @method get(int $id, ?array $field = []) 获取一条数据
 * @method save(array $data) 保存数据
 * @method update($id, array $data, ?string $key = null) 修改数据
 */
class SystemGroupDataServices extends BaseServices
{
    /**
     * SystemGroupDataServices constructor.
     * @param SystemGroupDataDao $dao
     */
    public function __construct(SystemGroupDataDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * 获取某个配置下的数据从新组合成新得数据返回
     * @param string $configName
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getConfigNameValue(string $configName, int $limit = 0)
    {
        /** @var SystemGroupServices $systemGroupServices */
        $systemGroupServices = app()->make(SystemGroupServices::class);
        $value = $this->dao->getGroupDate((int)$systemGroupServices->getConfigNameId($configName), $limit);
        $data = [];
        foreach ($value as $key => $item) {
            $data[$key]["id"] = $item["id"];
            if (isset($item['status'])) $data[$key]["status"] = $item["status"];
            $fields = json_decode($item["value"], true) ?: [];
            foreach ($fields as $index => $field) {
                if ($field['type'] == 'upload') {
                    $data[$key][$index] = set_file_url($field['value']);
                } else {
                    $data[$key][$index] = $field["value"];
                }
            }
        }
        return $data;
    }

    /**
     * 获取组合数据列表
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getGroupDataList(array $where)
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getGroupDataList($where, $page, $limit);
        $count = $this->dao->count($where);
        $type = '';
        $system_group_id = (int)$where['system_group_id'];
        /** @var SystemGroupServices $services */
        $services = app()->make(SystemGroupServices::class);
        $group = $services->getOne(['id' => $system_group_id], 'id,config_name,fields');

        $header = json_decode($group['fields'], true) ?? [];
        $title = [];
        $param = [];
        foreach ($header as $item) {
            if ($group['config_name'] == 'order_details_images' && $item['title'] == 'order_status') {
                $status = str_replace("\r\n", "\n", $item["param"]);//防止不兼容
                $status = explode("\n", $status);
                if (is_array($status) && !empty($status)) {
                    foreach ($status as $index => $v) {
                        $vl = explode('=>', $v);
                        if (isset($vl[0]) && isset($vl[1])) {
                            $param[$vl[0]] = $vl[1];
                        }
                    }
                }
            }
            if ($item['type'] == 'upload' || $item['type'] == 'uploads') {
                $title[$item['title']] = [];
                $type = $item['title'];
            } else {
                $title[$item['title']] = '';
            }
        }
        foreach ($list as $key => $value) {
            $list[$key] = array_merge($value, $title);
            $infos = json_decode($value['value'], true) ?: [];
            foreach ($infos as $index => $info) {
                if ($group['config_name'] == 'order_details_images' && $index == 'order_status') {
                    $list[$key][$index] = ($param[$info['value']] ?? '') . '/' . $info['value'];
                } else {
                    if ($info['type'] == 'upload') {
                        $list[$key][$index] = [set_file_url($info['value'])];
                    } elseif ($info['type'] == 'checkbox') {
                        $list[$key][$index] = implode(",", $info["value"]);
                    } else {
                        $list[$key][$index] = $info['value'];
                    }
                }
            }
            unset($list[$key]['value']);
        }
        return compact('list', 'count', 'type');
    }

    /**
     * 根据system_group_id判断出是否能再次添加组合数据
     * @param int $system_group_id
     * @param int $count
     * @param string $key
     * @return bool
     */
    public function isGroupGidSave(int $system_group_id, int $count, string $key): bool
    {
        /** @var SystemGroupServices $services */
        $services = app()->make(SystemGroupServices::class);
        $configName = $services->value(['id' => $system_group_id], 'config_name');
        if ($configName == $key) {
            return $this->dao->count(['system_group_id' => $system_group_id]) >= $count;
        } else {
            return false;
        }
    }

    /**
     * 创建表单
     * @param int $system_group_id
     * @param array $groupData
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function createGroupForm(int $system_group_id, array $groupData = [])
    {
        $groupDataValue = isset($groupData["value"]) ? json_decode($groupData["value"], true) : [];
        /** @var SystemGroupServices $services */
        $services = app()->make(SystemGroupServices::class);
        $fields = $services->getValueFields($system_group_id);
        $f[] = Form::hidden('system_group_id', $system_group_id);
        foreach ($fields as $key => $value) {
            $info = [];
            if (isset($value["param"])) {
                $value["param"] = str_replace("\r\n", "\n", $value["param"]);//防止不兼容
                $params = explode("\n", $value["param"]);
                if (is_array($params) && !empty($params)) {
                    foreach ($params as $index => $v) {
                        $vl = explode('=>', $v);
                        if (isset($vl[0]) && isset($vl[1])) {
                            $info[$index]["value"] = $vl[0];
                            $info[$index]["label"] = $vl[1];
                        }
                    }
                }
            }
            $fvalue = $groupDataValue[$value['title']]['value'] ?? '';
            switch ($value["type"]) {
                case 'input':
                    if ($system_group_id == 55 && $value['title'] == 'sign_num') {
                        $f[] = Form::number($value["title"], $value["name"], (int)$fvalue)->precision(0);
                    } else {
                        $f[] = Form::input($value["title"], $value["name"], $fvalue);
                    }
                    break;
                case 'textarea':
                    $f[] = Form::input($value["title"], $value["name"], $fvalue)->type('textarea')->placeholder($value['param']);
                    break;
                case 'radio':
                    $f[] = Form::radio($value["title"], $value["name"], $fvalue ?: (int)$info[0]["value"] ?? '')->options($info);
                    break;
                case 'checkbox':
                    $f[] = Form::checkbox($value["title"], $value["name"], $fvalue ?: $info[0] ?? '')->options($info);
                    break;
                case 'select':
                    $f[] = Form::select($value["title"], $value["name"], $fvalue !== '' ? $fvalue : $info[0] ?? '')->options($info)->multiple(false);
                    break;
                case 'upload':
                    if (!empty($fvalue)) {
                        $image = is_string($fvalue) ? $fvalue : $fvalue[0];
                    } else {
                        $image = '';
                    }
                    $f[] = Form::frameImage($value["title"], $value["name"], $this->url(config('app.admin_prefix', 'admin') . '/widget.images/index', ['fodder' => $value["title"], 'big' => 1], true), $image)->icon('el-icon-picture-outline')->width('950px')->height('560px')->props(['footer' => false]);
                    break;
                case 'uploads':
                    if ($fvalue) {
                        if (is_string($fvalue)) $fvalue = [$fvalue];
                        $images = !empty($fvalue) ? $fvalue : [];
                    } else {
                        $images = [];
                    }
                    $f[] = Form::frameImages($value["title"], $value["name"], $this->url(config('app.admin_prefix', 'admin') . '/widget.images/index', ['fodder' => $value["title"], 'big' => 1, 'type' => 'many', 'maxLength' => 5], true), $images)->maxLength(5)->icon('el-icon-picture-outline')->width('950px')->height('560px')->props(['footer' => false])->spin(0);
                    break;
                default:
                    $f[] = Form::input($value["title"], $value["name"], $fvalue);
                    break;

            }
        }
        $f[] = Form::number('sort', '排序', (int)($groupData["sort"] ?? 1))->precision(0);
        $f[] = Form::radio('status', '状态', (int)($groupData["status"] ?? 1))->options([['value' => 1, 'label' => '显示'], ['value' => 0, 'label' => '隐藏']]);
        return $f;
    }

    /**
     * 获取添加组合数据表单
     * @param int $system_group_id
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function createForm(int $system_group_id)
    {
        return create_form('添加数据', $this->createGroupForm($system_group_id), $this->url('/setting/group_data'));
    }

    /**
     * 获取修改组合数据表单
     * @param int $system_group_id
     * @param int $id
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function updateForm(int $system_group_id, int $id)
    {
        $groupData = $this->dao->get($id);
        if (!$groupData) {
            throw new AdminException(100026);
        }
        return create_form('编辑数据', $this->createGroupForm($system_group_id, $groupData->toArray()), $this->url('/setting/group_data/' . $id), 'PUT');
    }

    /**
     * 根据id获取当前记录中的数据
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getDateValue($id)
    {
        $value = $this->dao->get($id);
        $data["id"] = $value["id"];
        $data["status"] = $value["status"];
        $fields = json_decode($value["value"], true);
        foreach ($fields as $index => $field) {
            $data[$index] = $field["value"];
        }
        return $data;
    }

    /**
     * 根据id获取数据
     * @param array $ids
     * @param string $field
     */
    public function getGroupDataColumn(array $ids)
    {
        $systemGroup = [];
        if (!empty($ids)) {
            $systemGroupData = $this->dao->idByGroupList($ids);
            if (!empty($systemGroupData))
                $systemGroup = array_combine(array_column($systemGroupData, 'id'), $systemGroupData);
        }
        return $systemGroup;
    }

    /**
     * 根据system_group_id删除数据
     * @param int $system_group_id
     * @return mixed
     */
    public function delGroupDate(int $system_group_id)
    {
        return $this->dao->delGroupDate($system_group_id);
    }

    /**
     * 批量保存
     * @param array $params
     * @param string $config_name
     * @return bool
     * @throws \Exception
     */
    public function saveAllData(array $params, string $config_name)
    {
        /** @var SystemGroupServices $systemGroupServices */
        $systemGroupServices = app()->make(SystemGroupServices::class);
        $system_group_id = $systemGroupServices->value(['config_name' => $config_name], 'id');
        if (!$system_group_id) throw new AdminException(100026);
        $group = $systemGroupServices->getOne(['id' => $system_group_id], 'id,config_name,fields');
        $fields = json_decode($group['fields'], true) ?? [];
        $this->transaction(function () use ($system_group_id, $params, $fields) {
            $this->delGroupDate($system_group_id);
            $data = [];
            $sort = count($params);
            foreach ($params as $k => $v) {
                $value = [];
                foreach ($v as $key => $param) {
                    foreach ($fields as $index => $field) {
                        if ($key == $field["title"]) {
                            if ($param == "") {
                                throw new AdminException(400607, ['name' => $field["name"]]);
                            } else {
                                $value[$key]["type"] = $field["type"];
                                $value[$key]["value"] = $param;
                            }
                        }
                    }
                }
                $data[$k] = [
                    "system_group_id" => $system_group_id,
                    "add_time" => time(),
                    "value" => json_encode($value),
                    "sort" => $sort,
                    "status" => $v["status"] ?? 1
                ];
                $sort--;
            }
            $this->dao->saveAll($data);
        });
        \app\mc\services\CacheService::clear();
        return true;
    }

    /**
     * 检查秒杀时间段
     * @param SystemGroupServices $services
     * @param $system_group_id
     * @param $params
     * @param int $id
     * @return mixed
     */
    public function checkSeckillTime(SystemGroupServices $services, $system_group_id, $params, $id = 0)
    {
        $name = $services->value(['id' => $system_group_id], 'config_name');
        if ($name == 'routine_seckill_time') {
            if ($params['time'] == '') {
                throw new AdminException(400190);
            }
            if (!$params['continued']) {
                throw new AdminException(400191);
            }
            if (!preg_match('/^(\d|1\d|2[0-3])$/', $params['time'])) {
                throw new AdminException(400192);
            }
            if (!preg_match('/^([1-9]|1\d|2[0-4])$/', $params['continued'])) {
                throw new AdminException(400193);
            }
            if (($params['time'] + $params['continued']) > 24) throw new AdminException(400194);
            $list = $this->dao->getColumn(['system_group_id' => $system_group_id], 'value', 'id');
            if ($id) unset($list[$id]);
            $times = $time = [];
            foreach ($list as $item) {
                $info = json_decode($item, true);
                for ($i = 0; $i < $info['continued']['value']; $i++) {
                    $times[] = $info['time']['value'] + $i;
                }
            }
            for ($i = 0; $i < $params['continued']; $i++) {
                $time[] = $params['time'] + $i;
            }
            foreach ($time as $v) {
                if (in_array($v, $times)) throw new AdminException(400195);
            }
        }
    }
}
