<?php
namespace app;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\db\exception\PDOException;
use think\facade\Log;
use think\Response;
use Throwable;

/**
 * 应用异常处理类
 */
class ExceptionHandle extends Handle
{
    /**
     * 不需要记录信息（日志）的异常类列表
     * @var array
     */
    protected $ignoreReport = [
        HttpException::class,
        HttpResponseException::class,
        ModelNotFoundException::class,
        DataNotFoundException::class,
        ValidateException::class,
    ];

    /**
     * 记录异常信息（包括日志或者其它方式记录）
     *
     * @access public
     * @param  Throwable $exception
     * @return void
     */
    public function report(Throwable $exception): void
    {
        // 使用内置的方式记录异常日志
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @access public
     * @param \think\Request   $request
     * @param Throwable $e
     * @return Response
     */
    public function render($request, Throwable $e): Response
    {
        if ($e instanceof \BadMethodCallException) {
            Log::write(json_encode($e->getTrace()), 'error');
        } elseif ($e instanceof PDOException) {
            Log::write(json_encode($e->getTrace()), 'error');
        } elseif ($e instanceof \InvalidArgumentException) {
            Log::write(json_encode($e->getTrace()), 'error');
        } else {
            Log::write($e->getMessage(), 'error');
        }

        // 添加自定义异常处理机制
        if ($request->isAjax()) {
            $data = [];
            if (env('APP_DEBUG', false)) {
                $data['message'] = $e->getMessage();
                $data['trace'] = $e->getTrace();
            }
            if ($e instanceof \app\mc\exceptions\AuthException) {
                return app('json')->error($e->getCode(), $e->getMessage(), $data);
            }
            if ($e instanceof \think\exception\HttpException) {
                return app('json')->error(200001, '接口不存在', $data);
            }
            if ($e instanceof \app\mc\exceptions\ApiException) {
                return app('json')->error(200001, $e->getMessage(), $data);
            }
            return app('json')->error(200001, '系统异常', $data);
        } else {
            if (env('APP_DEBUG', false)) {
                return parent::render($request, $e);
            }
            if ($e instanceof \think\exception\HttpResponseException) {
                return parent::render($request, $e);
            }
            if ($e instanceof \think\exception\HttpException) {
                return response('页面不存在');
            }
            return response('系统异常');
        }
    }
}
