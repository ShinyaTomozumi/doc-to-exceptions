<?php
/**
 * ErrorParameters.php
 */

namespace App\Exceptions;

/**
 * Error class
 */
class ErrorParameters
{
    protected int $code;
    protected string $resultCode;
    protected ?string $message;
    protected int $httpResponseCode;
    protected ?string $detailMessage = "";
    protected ?string $previous = null;
    protected ?string $debugMessage = "";

    /**
     * Create instance
     * @param int $code
     * @param string $resultCode
     * @param string|null $message
     * @param int|null $responseCode
     * @return $this
     */
    public static function create(int $code, string $resultCode, ?string $message = null, ?int $responseCode = 400): ErrorParameters|static
    {
        $params = new ErrorParameters();
        $params->code = $code;
        $params->resultCode = $resultCode;
        $params->message = $message ?? $resultCode;
        $params->setHttpResponseCode(config('app.default_error_code', 400));
        return $params;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @param int $httpResponseCode
     */
    public function setHttpResponseCode(int $httpResponseCode): void
    {
        $this->httpResponseCode = $httpResponseCode;
    }

    /**
     * @param string $detailMessage
     */
    public function setDetailMessage(string $detailMessage): void
    {
        $this->detailMessage = $detailMessage;
    }

    /**
     * @param \Exception|\Throwable $previous
     */
    public function setPreviousException(\Exception|\Throwable $previous): void
    {
        $this->previous = $previous;
    }

    /**
     * @param string $debugMessage
     */
    public function setDebugMessage(string $debugMessage): void
    {
        $this->debugMessage = $debugMessage;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getResultCode(): string
    {
        return $this->resultCode;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return int
     */
    public function getHttpResponseCode(): int
    {
        return $this->httpResponseCode;
    }

    /**
     * @return ?string
     */
    public function getDetailMessage(): ?string
    {
        return $this->detailMessage;
    }

    /**
     * @return mixed
     */
    public function getPrevious(): mixed
    {
        return $this->previous;
    }

    /**
     * @return ?string
     */
    public function getDebugMessage(): ?string
    {
        return $this->debugMessage;
    }
}
