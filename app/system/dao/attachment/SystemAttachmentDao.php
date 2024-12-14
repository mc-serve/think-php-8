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
declare (strict_types=1);

namespace app\system\dao\attachment;

use app\mc\basic\BaseDao;
use app\system\model\attachment\SystemAttachment;

/**
 *
 * Class SystemAttachmentDao
 * @package app\dao\attachment
 */
class SystemAttachmentDao extends BaseDao
{

    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return SystemAttachment::class;
    }

    /**
     * 获取图片列表
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList(array $where, int $page, int $limit)
    {
        return $this->search($where)->where('module_type', 1)->page($page, $limit)->order('att_dir DESC')->select()->toArray();
    }

    /**
     * 移动图片
     * @param array $data
     * @return \app\mc\basic\BaseModel
     */
    public function move(array $data)
    {
        return $this->getModel()->whereIn('att_dir', $data['images'])->update(['pid' => $data['pid']]);
    }

    /**
     * 获取名称
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getLikeNameList(array $where, int $page, int $limit)
    {
        return $this->search($where)->page($page, $limit)->order('att_dir desc')->select()->toArray();
    }

    /**
     * 获取昨日系统生成
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getYesterday()
    {
        return $this->getModel()->whereTime('time', 'yesterday')->where('module_type', 2)->field(['name', 'att_dir', 'att_dir', 'image_type'])->select();
    }

    /**
     * 删除昨日生成海报
     * @throws \Exception
     */
    public function delYesterday()
    {
        $this->getModel()->whereTime('time', 'yesterday')->where('module_type', 2)->delete();
    }

    /**
     * 获取扫码上传的图片数据
     * @param $scan_token
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 扫地僧
     * @email cto@mc-serve.com
     * @date 2023/06/13
     */
    public function scanUploadImage($scan_token)
    {
        return $this->getModel()->where('scan_token', $scan_token)->field('att_dir,att_dir')->select()->toArray();
    }
}
