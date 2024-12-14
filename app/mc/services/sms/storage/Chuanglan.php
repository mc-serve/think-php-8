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

use app\mc\services\sms\BaseSms;
use app\mc\exceptions\AdminException;
use think\facade\Config;


/**
 * Class Chuanglan
 * @package app\mc\services\sms\storage
 */
class Chuanglan extends BaseSms
{

    /**
     * 开通
     */
    const SMS_OPEN = 'v2/sms_v2/open';

    /**
     * 修改签名
     */
    const SMS_MODIFY = 'v2/sms_v2/modify';

    /**
     * 用户信息
     */
    const SMS_INFO = 'v2/sms_v2/info';

    /**
     * 发送短信
     */
    const SMS_SEND = 'v2/sms_v2/send';

    /**
     * 短信模板
     */
    const SMS_TEMPS = 'v2/sms_v2/temps';

    /**
     * 申请模板
     */
    const SMS_APPLY = 'v2/sms_v2/apply';

    /**
     * 模板记录
     */
    const SMS_APPLYS = 'v2/sms_v2/applys';

    /**
     * 发送记录
     */
    const SMS_RECORD = 'v2/sms_v2/record';

    /**
     * 获取短信发送状态
     */
    const SMS_STSTUS = 'v2/sms/status';

    /**
     * 短信签名
     * @var string
     */
    protected $sign = '';

    /**
     * 模板id
     * @var array
     */
    protected $templateIds = [];

    /** 初始化
     * @param array $config
     */
    protected function initialize(array $config = [])
    {
        parent::initialize($config);
        $this->templateIds = Config::get($this->configFile . '.stores.' . $this->name . '.template_id', []);

    }

    /**
     * 提取模板code
     * @param string $templateId
     * @return null
     */
    protected function getTemplateCode(string $templateId)
    {
        return $this->templateIds[$templateId] ?? null;
    }

    /**
     * 设置签名
     * @param $sign
     * @return $this
     */
    public function setSign($sign)
    {
        $this->sign = $sign;
        return $this;
    }

    /**
     * 获取验证码
     * @param string $phone
     * @return array|mixed
     */
    public function captcha(string $phone)
    {
        $params = [
            'phone' => $phone
        ];
        return $this->accessToken->httpRequest('sms/captcha', $params, 'GET', false);
    }

    /**
     * 开通服务
     * @return array|bool|mixed
     */
    public function open()
    {
        $param = [
            'sign' => $this->sign
        ];
        return $this->accessToken->httpRequest(self::SMS_OPEN, $param);
    }

    /**
     * 修改签名
     * @param string $sign
     * @return array|bool|mixed
     */
    public function modify(string $sign = null, string $phone, string $code)
    {
        $param = [
            'sign' => $sign ?: $this->sign,
            'verify_code' => $code,
            'phone' => $phone
        ];
        return $this->accessToken->httpRequest(self::SMS_MODIFY, $param);
    }

    /**
     * 获取用户信息
     * @return array|bool|mixed
     */
    public function info()
    {
        return $this->accessToken->httpRequest(self::SMS_INFO, []);
    }

    /**
     * 获取短信模板
     * @param int $page
     * @param int $limit
     * @param int $type
     * @return array|mixed
     */
    public function temps(int $page = 0, int $limit = 10, int $type = 1)
    {
        $param = [
            'page' => $page,
            'limit' => $limit,
            'temp_type' => $type
        ];
        return $this->accessToken->httpRequest(self::SMS_TEMPS, $param);
    }

    /**
     * 申请模版
     * @param $title
     * @param $content
     * @param $type
     * @return array|bool|mixed
     */
    public function apply(string $title, string $content, int $type)
    {
        $param = [
            'title' => $title,
            'content' => $content,
            'type' => $type
        ];
        return $this->accessToken->httpRequest(self::SMS_APPLY, $param);
    }

    /**
     * 申请记录
     * @param $temp_type
     * @param int $page
     * @param int $limit
     * @return array|bool|mixed
     */
    public function applys(int $tempType, int $page, int $limit)
    {
        $param = [
            'temp_type' => $tempType,
            'page' => $page,
            'limit' => $limit
        ];
        return $this->accessToken->httpRequest(self::SMS_APPLYS, $param);
    }

    /**
     * 发送短信
     * @param $phone
     * @param $template
     * @param $param
     * @return bool|string
     */
    public function send(string $phone, string $templateId, array $data = [])
    {
        if (!$phone) {
            throw new AdminException(400719);
        }
        $param = [
            'phone' => $phone,
            'host' =>  request()->host()
        ];
        $param['temp_id'] = $this->getTemplateCode($templateId);
        if (is_null($param['temp_id'])) {
            throw new AdminException(400720);
        }
        $param['param'] = json_encode($data);
        return $this->accessToken->httpRequest(self::SMS_SEND, $param);
    }

    /**
     * 发送记录
     * @param $record_id
     * @return array|bool|mixed
     */
    public function record($record_id)
    {
        $param = [
            'record_id' => $record_id
        ];
        return $this->accessToken->httpRequest(self::SMS_RECORD, $param);
    }

    /**
     * 获取发送状态
     * @param array $recordIds
     * @return array|mixed
     */
    public function getStatus(array $recordIds)
    {
        $data['record_id'] = json_encode($recordIds);
        return $this->accessToken->httpRequest(self::SMS_STSTUS, $data, 'POST', false);
    }
}
