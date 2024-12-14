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

namespace app\system\services\store;


use app\system\dao\store\SystemStoreStaffDao;
use app\mc\basic\BaseServices;
use app\mc\exceptions\AdminException;
use app\mc\services\FormBuilder;

/**
 * 门店店员
 * Class SystemStoreStaffServices
 * @package app\system\services\store
 * @mixin SystemStoreStaffDao
 */
class SystemStoreStaffServices extends BaseServices
{
    /**
     * @var FormBuilder
     */
    protected $builder;

    /**
     * 构造方法
     * SystemStoreStaffServices constructor.
     * @param SystemStoreStaffDao $dao
     * @param FormBuilder $builder
     */
    public function __construct(SystemStoreStaffDao $dao, FormBuilder $builder)
    {
        $this->dao = $dao;
        $this->builder = $builder;
    }

    /**
     * 判断是否是有权限核销的店员
     * @param $user_id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function verifyStatus($user_id)
    {
        return (bool)$this->dao->getOne(['user_id' => $user_id, 'status' => 1, 'verify_status' => 1]);
    }

    /**
     * 获取店员列表
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getStoreStaffList(array $where)
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getStoreStaffList($where, $page, $limit);
        $count = $this->dao->count($where);
        return compact('list', 'count');
    }

    /**
     * 获取select选择框中的门店列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getStoreSelectFormData()
    {
        /** @var SystemStoreServices $service */
        $service = app()->make(SystemStoreServices::class);
        $menus = [];
        foreach ($service->getStore() as $menu) {
            $menus[] = ['value' => $menu['id'], 'label' => $menu['name']];
        }
        return $menus;
    }

    /**
     * 获取核销员表单
     * @param array $formData
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function createStoreStaffForm(array $formData = [])
    {
        if ($formData) {
            $field[] = $this->builder->frameImage('image', '更换头像', $this->url(config('app.admin_prefix', 'admin') . '/widget.images/index', array('fodder' => 'image'),true), $formData['avatar'] ?? '')->icon('el-icon-user')->width('950px')->height('560px')->props(['footer' => false]);
        } else {
            $field[] = $this->builder->frameImage('image', '商城用户', $this->url(config('app.admin_prefix', 'admin') . '/system.User/list', ['fodder' => 'image'], true))->icon('el-icon-user')->width('950px')->height('560px')->Props(['srcKey' => 'image', 'footer' => false]);
        }
        $field[] = $this->builder->hidden('user_id', $formData['user_id'] ?? 0);
        $field[] = $this->builder->hidden('avatar', $formData['avatar'] ?? '');
        $field[] = $this->builder->select('store_id', '所属提货点', ($formData['store_id'] ?? ''))->setOptions($this->getStoreSelectFormData())->filterable(true);
        $field[] = $this->builder->input('staff_name', '核销员名称', $formData['staff_name'] ?? '')->col(24)->required();
        $field[] = $this->builder->input('phone', '手机号码', $formData['phone'] ?? '')->col(24)->required();
        $field[] = $this->builder->radio('verify_status', '核销开关', $formData['verify_status'] ?? 1)->options([['value' => 1, 'label' => '开启'], ['value' => 0, 'label' => '关闭']]);
        $field[] = $this->builder->radio('status', '状态', $formData['status'] ?? 1)->options([['value' => 1, 'label' => '开启'], ['value' => 0, 'label' => '关闭']]);
        return $field;
    }

    /**
     * 添加核销员表单
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function createForm()
    {
        return create_form('添加核销员', $this->createStoreStaffForm(), $this->url('/merchant/store_staff/save/0'));
    }

    /**
     * 编辑核销员form表单
     * @param int $id
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function updateForm(int $id)
    {
        $storeStaff = $this->dao->get($id);
        if (!$storeStaff) {
            throw new AdminException(100026);
        }
        return create_form('修改核销员', $this->createStoreStaffForm($storeStaff->toArray()), $this->url('/merchant/store_staff/save/' . $id));
    }

}
