<?php

namespace App\Exceptions\Api;

use App\Exceptions\BaseApiException;
use App\Exceptions\ErrorParameters;
use Throwable;

/**
 * Class __class_name__
 * __comment__
 * @package App\Exceptions
 */
class __class_name__ extends BaseApiException
{
    protected string $debugMessage = ""; // DebugMessage
    protected string $detail = ""; // Detail message
    protected ?Throwable $previous = null; // Throwable

    function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Set debug message
     * @param string $debug
     * @return void
     */
    public function setDebugMessage(string $debug): void
    {
        $this->debugMessage = $debug;
    }

    /**
     * Set detail message
     * @param string $detail
     * @return void
     */
    public function setDetail(string $detail): void
    {
        $this->detail = $detail;
    }

    /**
     * Set Throwable
     * @param Throwable $previous
     * @return void
     */
    public function setThrowable(Throwable $previous): void
    {
        $this->previous = $previous;
    }

    /**
     * Return error messages / エラーメッセージの返却
     * @return ErrorParameters
     */
    protected function getErrorParams(): ErrorParameters
    {
        $errorParameters = ErrorParameters::create(__code__, "__result__", "__message__");
        __response_code__
        if ($this->debugMessage) {
            $errorParameters->setDebugMessage($this->debugMessage);
        }
        if ($this->previous) {
            $errorParameters->setPreviousException($this->previous);
        }
        if ($this->detail) {
            $errorParameters->setDetailMessage($this->detail);
        }
        return $errorParameters;
    }
}
