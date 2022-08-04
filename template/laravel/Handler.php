<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

/**
 * Common processing when an error occurs / エラー発生時の共通処理
 */
class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpExceptionInterface::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
    ];


    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param Exception|Throwable $e
     * @return mixed|void
     * @throws Exception
     */
    public function report(Exception|Throwable $e)
    {
        parent::report($e);
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param $request
     * @param Exception|Throwable $e
     * @return mixed
     * @throws Throwable
     */
    public function render($request, Exception|Throwable $e): mixed
    {
        // Output error log
        if ($e !== null) {
            \Log::error($e->getMessage() . $e->getTraceAsString());
        }


        // Return in json format for API access
        // APIでのアクセスの場合はjson形式で返却する
        $apiBaseName = env("API_BASE_NAME");
        if ($apiBaseName == null) {
            // Set End point
            $apiBaseName = "apis/*";
        }
        if ($request->is($apiBaseName) || $request->ajax()) {
            if ($e instanceof BaseApiException) {
                return $e->renderToJson($request);
            } else {
                // フレームワークなどの例外は一度アプリケーションの例外に変換する
                $apiException = ApiExceptionFactory::fromException($e);
                return $apiException->renderToJson($request, $e);
            }
        }


        // In case of errors due to web access, return the screen to be displayed according to the code.
        // Webのアクセスによるエラーの場合はコードによって表示する画面を返却する
        if ($this->isHttpException($e)) {

            /** @var HttpException $e */
            $statusCode = $e->getStatusCode();

            // error 403
            if ($statusCode == 403) {
                return response()->view("errors.403", ["menuName" => ""]);
            }
            // error 404
            if ($statusCode == 404) {
                return response()->view("errors.404", ["menuName" => ""]);
            }
            // error 500
            if ($statusCode == 500) {
                return response()->view("errors.500", ["menuName" => ""]);
            }
        }


        // For other errors, return the standard error page.
        // その他エラーは標準のエラーページを返却する。
        return parent::render($request, $e);
    }
}
