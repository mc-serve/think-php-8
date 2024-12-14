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
namespace app\mc\utils;

use Exception;

/**
 * 签名计算
 * Class fileVerification
 * @package app\mc\utils
 */
class fileVerification
{
    public $path = "";
    public $fileValue = "";

    /**
     * 项目路径
     * @param string $path
     * @return string
     * @throws Exception
     */
    public function getSignature(string $path): string
    {
        if (!is_dir($path) && !is_file($path)) {
            throw new Exception($path . " 不是有效的文件或目录!");
        }

        $appPath = $path . DS . 'app';
        if (!is_dir($appPath)) {
            throw new Exception($appPath . " 不是有效的目录!");
        }

        $mcPath = $path . DS . 'mc';
        if (!is_dir($mcPath)) {
            throw new Exception($mcPath . " 不是有效的目录!");
        }

        $this->path = $appPath;
        $this->getFileSignature($appPath);
        $this->path = $mcPath;
        $this->getFileSignature($mcPath);
        return md5($this->fileValue);
    }

    /**
     * 计算签名
     * @param string $path
     * @return void
     * @throws Exception
     */
    public function getFileSignature(string $path)
    {
        if (!is_dir($path)) {
            $this->fileValue .= @md5_file($path);
        } else {
            if (!$dh = opendir($path)) throw new Exception($path . " File open failed!");
            while (($file = readdir($dh)) != false) {
                if ($file == "." || $file == "..") {
                    continue;
                } else {
                    $this->getFileSignature($path . DS . $file);
                }
            }
            closedir($dh);
        }
    }
}