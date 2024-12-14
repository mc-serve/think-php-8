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

namespace app\admin\controller;

use app\Request;
use app\system\services\attachment\SystemAttachmentServices;
use app\system\services\SystemRouteServices;
use app\mc\services\CacheService;
use think\Response;

class Mc
{

    /**
     * 下载文件
     * @param string $key
     * @return Response|\think\response\File
     */
    public function download(string $key = '')
    {
        if (!$key) {
            return Response::create()->code(500);
        }
        $fileName = CacheService::get($key);
        if (is_array($fileName) && isset($fileName['path']) && isset($fileName['fileName']) && $fileName['path'] && $fileName['fileName'] && file_exists($fileName['path'])) {
            CacheService::delete($key);
            return download($fileName['path'], $fileName['fileName']);
        }
        return Response::create()->code(500);
    }

    /**
     * 获取workerman请求域名
     * @return mixed
     */
    public function getWorkerManUrl()
    {
        return app('json')->success(getWorkerManUrl());
    }

    /**
     * 扫码上传
     * @param Request $request
     * @param int $upload_type
     * @param int $type
     * @return Response
     * @author 扫地僧
     * @email cto@mc-serve.com
     * @date 2023/06/13
     */
    public function scanUpload(Request $request, $upload_type = 0, $type = 0)
    {
        [$file, $uploadToken, $pid] = $request->postMore([
            ['file', 'file'],
            ['uploadToken', ''],
            ['pid', 0]
        ], true);
        $service = app()->make(SystemAttachmentServices::class);
        if (CacheService::get('scan_upload') != $uploadToken) {
            return app('json')->fail(410086);
        }
        $service->upload((int)$pid, $file, $upload_type, $type, '', $uploadToken);
        return app('json')->success(100032);
    }

    public function import(Request $request)
    {
        $filePath = $request->param('file_path', '');
        if (empty($filePath)) {
            return app('json')->fail(12894);
        }
        app()->make(SystemRouteServices::class)->import($filePath);
        return app('json')->success(100010);
    }

    /**
     * 查询购买版权
     * @return mixed
     */
    public function copyright()
    {
        $copyrightContext = sys_config('nncnL_mc_copyright', '');
        $copyrightImage = sys_config('nncnL_mc_copyright_image', '');
        return app('json')->success(compact('copyrightContext', 'copyrightImage'));
    }

    /**
     * 获取logo
     * @return mixed
     */
    public function getLogo()
    {
        return app('json')->success([
            'logo' => sys_config('site_logo'),
            'logo_square' => sys_config('site_logo_square'),
            'site_name' => sys_config('site_name')
        ]);
    }
}
