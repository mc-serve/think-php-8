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

namespace app\mc\traits;


use app\mc\utils\Queue;

/**
 * 快捷加入消息队列
 * Trait QueueTrait
 * @package app\mc\traits
 */
trait QueueTrait
{
    /**
     * 列名
     * @return null
     */
    protected static function queueName()
    {
        return null;
    }

    /**
     * 加入队列
     * @param $action
     * @param array $data
     * @param string|null $queueName
     * @return mixed
     */
    public static function dispatch($action, array $data = [], string $queueName = null)
    {
        if (sys_config('queue_open', 0) == 1) {
            $queue = Queue::instance()->job(__CLASS__);
            if (is_array($action)) {
                $queue->data(...$action);
            } else if (is_string($action)) {
                $queue->do($action)->data(...$data);
            }
            if ($queueName) {
                $queue->setQueueName($queueName);
            } else if (static::queueName()) {
                $queue->setQueueName(static::queueName());
            }
            return $queue->push();
        } else {
            $className = '\\' . __CLASS__;
            $res = new $className();
            if (is_array($action)) {
                $res->doJob(...$action);
            } else {
                $res->$action(...$data);
            }
        }
    }

    /**
     * 延迟加入消息队列
     * @param int $secs
     * @param $action
     * @param array $data
     * @param string|null $queueName
     * @return mixed
     */
    public static function dispatchSecs(int $secs, $action, array $data = [], string $queueName = null)
    {
        if (sys_config('queue_open', 0) == 1) {
            $queue = Queue::instance()->job(__CLASS__)->secs($secs);
            if (is_array($action)) {
                $queue->data(...$action);
            } else if (is_string($action)) {
                $queue->do($action)->data(...$data);
            }
            if ($queueName) {
                $queue->setQueueName($queueName);
            } else if (static::queueName()) {
                $queue->setQueueName(static::queueName());
            }
            return $queue->push();
        }
    }
}
