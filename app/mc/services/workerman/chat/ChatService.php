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


use app\services\kefu\service\StoreServiceRecordServices;
use app\services\kefu\service\StoreServiceServices;
use Channel\Client;
use app\mc\services\workerman\ChannelService;
use app\mc\services\workerman\Response;
use Workerman\Connection\TcpConnection;
use Workerman\Lib\Timer;
use Workerman\Worker;

class ChatService
{
    /**
     * @var Worker
     */
    protected $worker;

    /**
     * @var TcpConnection[]
     */
    protected $connections = [];

    /**
     * @var TcpConnection[]
     */
    protected $user = [];

    /**
     * 在线客服
     * @var TcpConnection[]
     */
    protected $kefuUser = [];

    /**
     * @var ChatHandle
     */
    protected $handle;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var int
     */
    protected $timer;

    public function __construct(Worker $worker)
    {
        $this->worker = $worker;
        $this->handle = new ChatHandle($this);
        $this->response = new Response();
    }

    public function setUser(TcpConnection $connection)
    {
        $this->user[$connection->user->user_id] = $connection;
    }

    /**
     * 获得当前在线客服
     * @return TcpConnection[]
     */
    public function kefuUser()
    {
        return $this->kefuUser;
    }

    /**
     * 设置当前在线客服
     * @param TcpConnection $connection
     */
    public function setKefuUser(TcpConnection $connection, bool $isUser = true)
    {
        $this->kefuUser[$connection->kefuUser->user_id] = $connection;
        if ($isUser) {
            $this->user[$connection->user->user_id] = $connection;
        }
    }

    public function user($key = null)
    {
        return $key ? ($this->user[$key] ?? false) : $this->user;
    }

    public function onConnect(TcpConnection $connection)
    {
        $this->connections[$connection->id] = $connection;
        $connection->lastMessageTime = time();
    }

    public function onMessage(TcpConnection $connection, $res)
    {
        $connection->lastMessageTime = time();
        $res = json_decode($res, true);
        if (!$res || !isset($res['type']) || !$res['type'] || $res['type'] == 'ping') {
            return $this->response->connection($connection)->success('ping', ['now' => time()]);
        }
        if (!method_exists($this->handle, $res['type'])) return;
        try {
            $this->handle->{$res['type']}($connection, $res + ['data' => []], $this->response->connection($connection));
        } catch (\Throwable $e) {
        }
    }


    public function onWorkerStart(Worker $worker)
    {
        // ChannelService::connet();

        // Client::on('mc_chat', function ($eventData) use ($worker) {
        //     if (!isset($eventData['type']) || !$eventData['type']) return;
        //     $ids = isset($eventData['ids']) && count($eventData['ids']) ? $eventData['ids'] : array_keys($this->user);
        //     $fun = $eventData['fun'] ?? false;
        //     foreach ($ids as $id) {
        //         if (isset($this->user[$id])) {
        //             if ($fun) {
        //                 $this->handle->{$eventData['type']}($this->user[$id], $eventData + ['data' => []], $this->response->connection($this->user[$id]));
        //             } else {
        //                 $this->response->connection($this->user[$id])->success($eventData['type'], $eventData['data'] ?? null);
        //             }
        //         }
        //     }
        // });

        $this->timer = Timer::add(15, function () use (&$worker) {
            $time_now = time();
            foreach ($worker->connections as $connection) {
                if ($time_now - $connection->lastMessageTime > 12) {
                    //定时器判断当前用户是否下线
                    if (isset($connection->user->user_id) && !isset($connection->user->isTourist)) {
                        /** @var StoreServiceRecordServices $service */
                        $service = app()->make(StoreServiceRecordServices::class);
                        $service->updateRecord(['to_user_id' => $connection->user->user_id], ['online' => 0]);
                    }
                    $this->response->connection($connection)->close('timeout');
                    //广播给客服谁下线了
                    foreach ($this->kefuUser as $user_id => &$conn) {
                        if (isset($connection->user->user_id) && $connection->user->user_id != $user_id) {
                            if (isset($conn->onlineuser_ids) && ($key = array_search($connection->user->user_id, $conn->onlineuser_ids)) !== false) {
                                unset($conn->onlineuser_ids[$key]);
                            }
                            $this->response->connection($conn)->send('user_online', ['to_user_id' => $connection->user->user_id, 'online' => 0]);
                        }
                    }
                }
            }
        });

        Timer::add(2, function () use (&$worker) {
            $user_ids = [];
            foreach ($this->user() as $user_id => $connection) {
                if (!isset($connection->isTourist)) {
                    $user_ids[] = $user_id;
                }
            }
            if ($user_ids) {
                //除了当前在线的其他全部都下线
                /** @var StoreServiceRecordServices $service */
                $service = app()->make(StoreServiceRecordServices::class);
                $service->updateOnline(['notuser_id' => $user_ids], ['online' => 0]);
            }
            $kefuuser_id = array_keys($this->kefuUser());
            if ($kefuuser_id) {
                /** @var StoreServiceServices $kefuService */
                $kefuService = app()->make(StoreServiceServices::class);
                $kefuService->updateOnline(['notuser_id' => $kefuuser_id], ['online' => 0]);
            }
        });
    }


    public function onClose(TcpConnection $connection)
    {
        var_dump('close');
        unset($this->connections[$connection->id]);
        if (isset($connection->user->user_id)) {
            unset($this->user[$connection->user->user_id]);
        }
        if (isset($connection->kefuUser->user_id)) {
            unset($this->kefuUser[$connection->kefuUser->user_id]);
        }
    }
}
