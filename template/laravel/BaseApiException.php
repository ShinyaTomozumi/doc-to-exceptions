<?php
namespace App\Exceptions;

use Error;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Base class for error handling for API / API用のエラー処理の基底クラス
 * Class BaseApiException
 * @package App\Exceptions
 */
abstract class BaseApiException extends \Exception
{
    /**
     * Return error information as Json / エラー情報をJsonにして返却する
     * @param Request $request
     * @param Exception|Error $ex
     * @param ErrorParameters $params
     * @return JsonResponse
     */
    protected function toJsonResponse(Request $request, Exception|Error $ex, ErrorParameters $params): JsonResponse
    {
        $response = [
            "result" => $params->getResultCode(),
            "code" => $params->getCode(),
            "message" => $params->getMessage(),
            "detail" => $params->getDetailMessage(),
        ];

        // For debug message
        $isDebug = env("APP_DEBUG", config("app.debug", false));
        if ($isDebug && $ex) {
            $response["debugMessage"] = $ex->getFile() . "(" . $ex->getLine() . ")";
            $response["debugTrace"] = explode("\n", $ex->getTraceAsString());
        }

        // Response json
        $headers = ["Content-type" => "application/json; charset=utf-8"];
        return response()->json($response, $params->getHttpResponseCode(), $headers, JSON_PRETTY_PRINT);
    }


    /**
     * Export to Json / Jsonに書き出し
     * @param Request $request
     * @param \Exception|\Error $originalException
     * @return JsonResponse
     */
    public function renderToJson(?Request $request, \Exception|\Error $originalException = null): JsonResponse
    {
        return $this->toJsonResponse($request, $originalException ?? $this, $this->getErrorParams());
    }


    abstract protected function getErrorParams(): ErrorParameters;
}
