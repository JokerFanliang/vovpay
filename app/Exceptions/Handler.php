<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * @param Exception $exception
     * @return mixed|void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // 登录验证
        if ($exception instanceof AuthenticationException) {
            // 判断是否返回json
            if ($request->expectsJson()) return ajaxError('您未登录');

            if ( in_array('admin', $exception->guards() ))
            {
                return redirect('/admin/login');
            }elseif( in_array('agent', $exception->guards() )){
                return redirect('/agent/login');
            }elseif( in_array('user', $exception->guards() )) {
                return redirect('/user/login');
            }elseif(in_array('court', $exception->guards() )){
                return redirect('/court/login');
            }
        }else if( $exception instanceof ValidationException) { // 表单验证错误
            if( $request->expectsJson() )
            {
                $error = array_flatten($exception->errors());
                return ajaxError(array_get($error,0));
            }else if( $request->path() == 'pay'){   // 支付接口验证返回json
                $error = array_flatten($exception->errors());
                return ajaxError(array_get($error,0));
            }
        }else if($exception instanceof TokenMismatchException) {
            if( $request->expectsJson() )
            {
                return ajaxError('非法操作');
            }
        }else if($exception instanceof CustomServiceException){ // services自定义报错
            return ajaxError($exception->getMessage());
        }else if($exception instanceof QueryException){
            if( $request->expectsJson() )
            {
                $exceptionArr = explode('(',$exception->getMessage());
                return ajaxError($exceptionArr[0]);
            }
        }else if($exception instanceof MethodNotAllowedHttpException){
            return ajaxError('非法操作');
        }else if($exception instanceof AccessDeniedHttpException){
            return ajaxError('没有操作权限');
        }
        return parent::render($request, $exception);
    }
}
