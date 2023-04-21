<?php
/**
 * AdminExceptionFactory.php
 */
namespace App\Exceptions;

use Illuminate\Validation\ValidationException;
use App\Exceptions\Api\ApiUnknownException;
use App\Exceptions\Api\ApiInvalidArgumentException;

/**
 * Class to create an error for the API in case of standard errors other than the defined errors.
 * 定義したエラー以外の標準のエラー時にAPI用のエラーを作成するクラス
 */
class ApiExceptionFactory
{
    protected function __construct()
    {
    }

    /**
     * Convert to an error for API in case of standard errors other than the defined errors.
     * 定義したエラー以外の標準のエラー時にAPI用のエラーに変換する。
     * @param \Exception|\Error $ex
     * @return BaseApiException
     */
    public static function fromException(\Exception|\Error $ex): BaseApiException
    {
        if ($ex instanceof \PDOException) {
            return new ApiUnknownException(500, "データベースへの接続に失敗しました。");
        } else if ($ex instanceof \InvalidArgumentException) {
            return new ApiInvalidArgumentException();
        } else if ($ex instanceof ValidationException) {
            return new ApiInvalidArgumentException();
        } else {
            return new ApiUnknownException(500, $ex->getMessage());
        }
    }
}
