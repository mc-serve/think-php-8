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

namespace app\mc\services\workerman\chat;

use app\services\kefu\LoginServices;
use app\services\kefu\service\StoreServiceLogServices;
use app\services\kefu\service\StoreServiceRecordServices;
use app\services\kefu\service\StoreServiceServices;
use app\services\order\StoreOrderServices;
use app\services\product\product\StoreProductServices;
use app\services\user\UserServices;
use app\services\wechat\WechatKeyServices;
use app\services\wechat\WechatReplyServices;
use app\services\wechat\WechatUserServices;
use app\services\user\UserAuthServices;
use app\mc\exceptions\AuthException;
use app\mc\services\app\WechatService;
use app\mc\services\workerman\Response;
use app\mc\utils\Arr;
use think\facade\Log;
use Workerman\Connection\TcpConnection;

/**
 * Class ChatHandle
 * @package app\mc\services\workerman\chat
 */
class ChatHandle
{
    /**
     * @var ChatService
     */
    protected $service;

    /**
     * ChatHandle constructor.
     * @param ChatService $service
     */
    public function __construct(ChatService &$service)
    {
        $this->service = &$service;
    }

    /**
     * 客服登录
     * @param TcpConnection $connection
     * @param array $res
     * @param Response $response
     * @return bool|null
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function kefu_login(TcpConnection &$connection, array $res, Response $response)
    {
        if (!isset($res['data']) || !$token = $res['data']) {
            return $response->close([
                'msg' => '授权失败!'
            ]);
        }
        try {
            /** @var LoginServices $services */
            $services = app()->make(LoginServices::class);
            $kefuInfo = $services->parseToken($token);
        } catch (AuthException $e) {
            return $response->close([
                'msg' => $e->getMessage()
            ]);
        }

        $connection->kefuUser = $kefuInfo;
        /** @var UserServices $userService */
        $userService = app()->make(UserServices::class);
        $connection->user = $userService->get($kefuInfo['user_id'], ['id', 'nickname']);
        if (!isset($connection->user->user_id)) {
            return $response->close([
                'msg' => '您登录的客服用户不存在'
            ]);
        }
        /** @var StoreServiceRecordServices $service */
        $service = app()->make(StoreServiceRecordServices::class);
        $service->updateRecord(['to_user_id' => $connection->user->user_id], ['online' => 1]);
        /** @var StoreServiceServices $service */
        $service = app()->make(StoreServiceServices::class);
        $service->update(['user_id' => $connection->user->user_id], ['online' => 1]);
        $this->service->setKefuUser($connection);

        return $response->success();
    }

    /**
     * 用户登录
     * @param TcpConnection $connection
     * @param array $res
     * @param Response $response
     * @return bool|null
     */
    public function login(TcpConnection &$connection, array $res, Response $response)
    {
        if (!isset($res['data']) || !$token = $res['data']) {
            return $response->close([
                'msg' => '授权失败!'
            ]);
        }

        try {
            /** @var UserAuthServices $services */
            $services = app()->make(UserAuthServices::class);
            $authInfo = $services->parseToken($token);
        } catch (AuthException $e) {
            return $response->close([
                'msg' => $e->getMessage()
            ]);
        }

        $connection->user = $authInfo['user'];
        $connection->tokenData = $authInfo['tokenData'];
        $this->service->setUser($connection);

        /** @var StoreServiceRecordServices $service */
        $service = app()->make(StoreServiceRecordServices::class);
        $service->updateRecord(['to_user_id' => $connection->user->user_id], ['online' => 1, 'type' => $res['form_type'] ?? 1]);
        $connections = $this->service->kefuUser();
        foreach ($connections as &$conn) {
            if (!isset($conn->onlineuser_ids) || !in_array($connection->user->user_id, $conn->onlineuser_ids ?? [])) {
                $response->connection($conn)->send('user_online', ['to_user_id' => $connection->user->user_id, 'online' => 1]);
            }
            if (!isset($conn->onlineuser_ids)) {
                $conn->onlineuser_ids = [];
            }
            array_push($conn->onlineuser_ids, $connection->user->user_id);
            $this->service->setKefuUser($conn, false);
        }

        return $response->connection($connection)->success();
    }

    /**
     *
     * @param TcpConnection $connection
     * @param array $res
     * @param Response $response
     */
    public function to_chat(TcpConnection &$connection, array $res, Response $response)
    {
        $tourist_user_id = $res['data']['tourist_user_id'] ?? 0;
        if ($tourist_user_id) {
            $connection->isTourist = true;
            $connection->user = (object)['user_id' => $tourist_user_id];
            $connections = $this->service->user();
            if (!isset($connections[$tourist_user_id])) {
                $this->service->setUser($connection);
            }
        }
        $connection->chatTouser_id = $res['data']['id'] ?? 0;
        if (isset($connection->user)) {
            $user_id = $connection->user->user_id;
            if ($connection->chatTouser_id && !isset($connection->isTourist)) {
                /** @var StoreServiceRecordServices $service */
                $service = app()->make(StoreServiceRecordServices::class);
                $service->update(['user_id' => $user_id, 'to_user_id' => $connection->chatTouser_id], ['mssage_num' => 0]);
                /** @var StoreServiceLogServices $logServices */
                $logServices = app()->make(StoreServiceLogServices::class);
                $logServices->update(['user_id' => $connection->chatTouser_id, 'to_user_id' => $user_id], ['type' => 1]);
            }
            $response->send('mssage_num', ['user_id' => $connection->chatTouser_id, 'num' => 0, 'recored' => (object)[]]);
        }
    }

    /**
     * 用户向客服发送消息
     * @param TcpConnection $connection
     * @param array $res
     * @param Response $response
     * @return bool|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function chat(TcpConnection &$connection, array $res, Response $response)
    {
        $to_user_id = $res['data']['to_user_id'] ?? 0;
        $msn_type = $res['data']['type'] ?? 0;
        $msn = $res['data']['msn'] ?? '';
        $formType = $res['form_type'] ?? 0;
        //是否为游客
        $isTourist = $res['data']['is_tourist'] ?? 0;
        $tourist_user_id = $res['data']['tourist_user_id'] ?? 0;
        $isTourist = $isTourist && $tourist_user_id;
        $tourist_avatar = $res['data']['tourist_avatar'] ?? '';
        $user_id = $isTourist ? $tourist_user_id : $connection->user->user_id;
        if (!$to_user_id) {
            return $response->send('err_tip', ['msg' => '用户不存在']);
        }
        if ($to_user_id == $user_id) {
            return $response->send('err_tip', ['msg' => '不能和自己聊天']);
        }
        /** @var StoreServiceLogServices $logServices */
        $logServices = app()->make(StoreServiceLogServices::class);
        if (!in_array($msn_type, $logServices::MSN_TYPE)) {
            return $response->send('err_tip', ['msg' => '格式错误']);
        }
        $msn = trim(strip_tags(str_replace(["\n", "\t", "\r", "&nbsp;"], '', htmlspecialchars_decode($msn))));
        $data = compact('to_user_id', 'msn_type', 'msn', 'user_id');
        $data['add_time'] = time();
        $data['is_tourist'] = $res['data']['is_tourist'] ?? 0;
        $connections = $this->service->user();
        $online = isset($connections[$to_user_id]) && isset($connections[$to_user_id]->chatTouser_id) && $connections[$to_user_id]->chatTouser_id == $user_id;
        $data['type'] = $online ? 1 : 0;
        $data = $logServices->save($data);
        $data = $data->toArray();
        $data['_add_time'] = $data['add_time'];
        $data['add_time'] = strtotime($data['add_time']);
        if (!$isTourist) {
            if (isset($this->service->kefuUser()[$data['user_id']])) {
                /** @var StoreServiceServices $userService */
                $userService = app()->make(StoreServiceServices::class);
                $_userInfo = $userService->get(['user_id' => $data['user_id']], ['nickname', 'avatar']);
            } else {
                /** @var UserServices $userService */
                $userService = app()->make(UserServices::class);
                $_userInfo = $userService->getUserInfo($data['user_id'], 'nickname,avatar');
            }
            $data['nickname'] = $_userInfo['nickname'];
            $data['avatar'] = $_userInfo['avatar'];
        } else {
            $avatar = sys_config('tourist_avatar');
            $_userInfo['avatar'] = $tourist_avatar ?: Arr::getArrayRandKey(is_array($avatar) ? $avatar : []);
            $_userInfo['nickname'] = '游客' . $user_id;
            $data['nickname'] = $_userInfo['nickname'];
            $data['avatar'] = $_userInfo['avatar'];
        }

        //商品消息类型
        $data['productInfo'] = [];
        if ($msn_type == StoreServiceLogServices::MSN_TYPE_GOODS && $msn) {
            /** @var StoreProductServices $productServices */
            $productServices = app()->make(StoreProductServices::class);
            $productInfo = $productServices->getProductInfo((int)$msn, 'store_name,IFNULL(sales,0) + IFNULL(ficti,0) as sales,image,slider_image,price,vip_price,ot_price,stock,id');
            $data['productInfo'] = $productInfo ? $productInfo->toArray() : [];
        }
        //订单消息类型
        $data['orderInfo'] = [];
        if ($msn_type == StoreServiceLogServices::MSN_TYPE_ORDER && $msn) {
            /** @var StoreOrderServices $orderServices */
            $orderServices = app()->make(StoreOrderServices::class);
            $order = $orderServices->getUserOrderDetail($msn, $user_id);
            if ($order) {
                $order = $orderServices->tidyOrder($order->toArray(), true, true);
                $order['add_time_y'] = date('Y-m-d', $order['add_time']);
                $order['add_time_h'] = date('H:i:s', $order['add_time']);
                $data['orderInfo'] = $order;
            }
        }
        //给自己回复消息
        $response->send('chat', $data);

        //用户向客服发送消息，判断当前客服是否在登录中
        /** @var StoreServiceRecordServices $serviceRecored */
        $serviceRecored = app()->make(StoreServiceRecordServices::class);
        $unMessagesCount = $logServices->getMessageNum(['user_id' => $user_id, 'to_user_id' => $to_user_id, 'type' => 0, 'is_tourist' => $isTourist ? 1 : 0]);
        //记录当前用户和他人聊天记录
        $data['recored'] = $serviceRecored->saveRecord($user_id, $to_user_id, $msn, $formType ?? 0, $msn_type, $unMessagesCount, $isTourist, $data['nickname'], $data['avatar']);
        //是否在线
        if ($online) {
            $response->connection($this->service->user()[$to_user_id])->send('reply', $data);
        } else {
            //用户在线，可是没有和当前用户进行聊天，给当前用户发送未读条数
            if (isset($connections[$to_user_id])) {
                $data['recored']['nickname'] = $_userInfo['nickname'];
                $data['recored']['avatar'] = $_userInfo['avatar'];
                $response->connection($this->service->user()[$to_user_id])->send('mssage_num', [
                    'user_id' => $user_id,
                    'num' => $unMessagesCount,
                    'recored' => $data['recored']
                ]);
            }
            if ($isTourist) {
                return true;
            }
            //用户不在线
            /** @var WechatUserServices $wechatUserServices */
            $wechatUserServices = app()->make(WechatUserServices::class);
            $userInfo = $wechatUserServices->getOne(['user_id' => $to_user_id, 'user_type' => 'wechat'], 'nickname,subscribe,openid,headimgurl');
            if ($userInfo && $userInfo['subscribe'] && $userInfo['openid']) {
                $description = '您有新的消息，请注意查收！';
                if ($formType) {
                    $head = '客服接待消息提醒';
                    $url = sys_config('site_url') . '/kefu/mobile_chat?touser_id=' . $user_id . '&nickname=' . $_userInfo['nickname'];
                } else {
                    $head = '客服回复消息提醒';
                    $url = sys_config('site_url') . '/pages/extension/customer_list/chat?user_id=' . $user_id;
                }
                $message = WechatService::newsMessage($head, $description, $url, $_userInfo['avatar']);
                $userInfo = $userInfo->toArray();
                try {
                    WechatService::staffService()->message($message)->to($userInfo['openid'])->send();
                } catch (\Exception $e) {

                    Log::error($userInfo['nickname'] . '发送失败' . $e->getMessage());
                }
            }
        }
        if (!isset($this->service->kefuUser()[$user_id])) {
            //判断是否有自动回复
            $wechatKeyServices = app()->make(WechatKeyServices::class);
            $replyId = $wechatKeyServices->value(['keys' => $msn, 'key_type' => 1], 'reply_id');
            if(!$replyId) $replyId = $wechatKeyServices->value(['keys_like' => $msn, 'key_type' => 1], 'reply_id');
            if ($replyId) {
                //查询回复内容
                $autoReplyData = app()->make(WechatReplyServices::class)->get($replyId)->toArray();
                $msgData = json_decode($autoReplyData['data'], true);
                $autoReplyMsn = $autoReplyData['type'] == 'text' ? $msgData['content'] : $msgData['src'];
                $autoReply['to_user_id'] = $user_id;
                $autoReply['msn_type'] = $autoReplyData['type'] == 'text' ? 1 : 3;
                $autoReply['msn'] = $autoReplyMsn;
                $autoReply['user_id'] = $to_user_id;
                $autoReply['add_time'] = time();
                $autoReply['is_tourist'] = 0;
                $autoReply['type'] = 1;
                $autoReply = $logServices->save($autoReply);
                $autoReply = $autoReply->toArray();
                $autoReply['_add_time'] = $autoReply['add_time'];
                $autoReply['add_time'] = strtotime($autoReply['add_time']);
                $kefuInfo = app()->make(StoreServiceServices::class)->get(['user_id' => $to_user_id], ['nickname', 'avatar']);
                $replyMessagesCount = $logServices->getMessageNum(['user_id' => $to_user_id, 'to_user_id' => $user_id, 'type' => 0, 'is_tourist' => $isTourist ? 1 : 0]);
                $autoReply['recored'] = $serviceRecored->saveRecord($to_user_id, $user_id, $autoReplyMsn, $formType ?? 0, $autoReplyData['type'] == 'text' ? 1 : 3, $replyMessagesCount, $isTourist, $kefuInfo['nickname'], $kefuInfo['avatar']);
                $response->connection($this->service->user()[$user_id])->send('reply', $autoReply);
            }
        }
    }

    /**
     * 上下线
     * @param TcpConnection $connection
     * @param array $res
     * @param Response $response
     */
    public function online(TcpConnection &$connection, array $res, Response $response)
    {
        $online = $res['data']['online'] ?? 0;
        $connections = $this->service->user();
        if (isset($connection->user->user_id)) {
            $user_ids = $connection->user->user_id;
            /** @var StoreServiceServices $service */
            $service = app()->make(StoreServiceServices::class);
            $service->update(['user_id' => $user_ids], ['online' => $online]);
            //广播给正在和自己聊天的用户
            foreach ($connections as $user_id => $conn) {
                if ($user_id !== $user_ids && $user_ids == ($conn->chatTouser_id ?? 0)) {
                    $response->connection($conn)->send('online', ['online' => $online, 'user_id' => $user_ids]);
                }
            }
        }
    }

    /**
     * 客服转接
     * @param TcpConnection $connection
     * @param array $res
     * @param Response $response
     */
    public function transfer(TcpConnection &$connection, array $res, Response $response)
    {
        $data = $res['data'] ?? [];
        $user_id = $data['recored']['user_id'] ?? 0;
        if ($user_id && $this->service->user($user_id)) {
            $data['recored']['online'] = 1;
        } else {
            $data['recored']['online'] = 0;
        }
        $response->send('transfer', $data);
    }
}
