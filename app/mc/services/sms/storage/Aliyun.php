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

namespace app\mc\services\sms\storage;

use AlibabaCloud\SDK\Dysmsapi\V20170525\Dysmsapi;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendSmsRequest;
use AlibabaCloud\Tea\Exception\TeaError;
use AlibabaCloud\Tea\Utils\Utils\RuntimeOptions;
use app\mc\exceptions\ApiException;
use app\mc\services\sms\BaseSms;
use Darabonba\OpenApi\Models\Config as AliConfig;
use think\exception\ValidateException;
use think\facade\Config;


/**
 * Class Aliyun
 * @package app\mc\services\sms\storage
 */
class Aliyun extends BaseSms
{
    protected $AccessKeyId = '';
    protected $AccessKeySecret = '';
    protected $SignName = '';

    /**
     * @param array $config
     * @return mixed|void
     */
    protected function initialize(array $config = [])
    {
        parent::initialize($config);
        $this->SignName = $config['aliyun_SignName'] ?? '';
        $this->AccessKeyId = $config['aliyun_AccessKeyId'] ?? '';
        $this->AccessKeySecret = $config['aliyun_AccessKeySecret'] ?? '';
    }

    /**
     * 发送短信
     * @param string $phone
     * @param string $templateId
     * @param array $data
     * @return array|bool|mixed
     */
    public function send(string $phone, string $templateId, array $data = [])
    {
        if (empty($phone)) {
            return $this->setError('电话号码不能为空');
        }

        $config = new AliConfig([
            "accessKeyId" => $this->AccessKeyId,
            "accessKeySecret" => $this->AccessKeySecret
        ]);
        // 访问的域名
        $config->endpoint = "dysmsapi.aliyuncs.com";
        $client = new Dysmsapi($config);

        if (!$templateId) throw new ApiException('模板不存在：' . $templateId);

        $sendSmsRequest = new SendSmsRequest([
            "phoneNumbers" => $phone,
            "signName" => $this->SignName,
            "templateCode" => $templateId,
            "templateParam" => json_encode($data),
        ]);
        $runtime = new RuntimeOptions([]);
        try {
            // 复制代码运行请自行打印 API 的返回值
            $resp = $client->sendSmsWithOptions($sendSmsRequest, $runtime);
            if (isset($resp) && $resp->body->code !== 'OK') {
                throw new ApiException('【阿里云平台错误提示】：' . $resp->body->message);
            }
            return [
                'id' => $resp->body->requestId,
                'content' => json_encode($data),
                'template' => $templateId,
            ];
        } catch (\Exception $e) {
            throw new ApiException('【阿里云平台错误提示】：' . $e->getMessage());
        }
    }

    public function open()
    {
    }

    public function modify(string $sign = null, string $phone, string $code)
    {
    }

    public function info()
    {
    }

    public function temps(int $page = 0, int $limit = 10, int $type = 1)
    {
    }

    public function apply(string $title, string $content, int $type)
    {
    }

    public function applys(int $tempType, int $page, int $limit)
    {
    }

    public function record($record_id)
    {
    }
}
