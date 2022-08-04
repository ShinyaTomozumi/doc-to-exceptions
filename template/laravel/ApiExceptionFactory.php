<?php
/**
 * AdminExceptionFactory.php
 */
namespace App\Exceptions;

use Illuminate\Validation\ValidationException;
use App\Exceptions\Api\ApiUnknownException;
use App\Exceptions\Api\ApiInvalidArgumentException;

/**
 * Class to create an error for the API when a standard PHP or Laravel error occurs.
 * PHPやLaravelでの標準のエラーが発生した場合にAPI用のエラーを作成するクラス
 */
class ApiExceptionFactory
{
    protected function __construct()
    {
    }

    /**
     * Convert PHP and Laravel standard error content to error content for API
     * PHPやLaravel標準のエラー内容をAPI用のエラー内容に変換する
     * @param \Exception|\Error $ex
     * @return BaseApiException
     */
    public static function fromException(\Exception|\Error $ex): BaseApiException
    {
        if ($ex instanceof \PDOException) {
            return new ApiUnknownException("データベースへの接続に失敗しました。");
        } else if ($ex instanceof \InvalidArgumentException) {
            return new ApiInvalidArgumentException();
        } else if ($ex instanceof ValidationException) {
            return new ApiInvalidArgumentException();
        } else {
            return new ApiUnknownException($ex->getMessage());
        }
    }
}
