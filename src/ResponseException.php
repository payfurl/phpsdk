<?php
namespace payFURL\Sdk;

/**
 * @copyright PayFURL
 */
final class ResponseException extends \Exception
{
    public $httpCode;
    public $isRetryable;
    public $type;
    public $resource;
    public $details;

    function __construct($Message, $Code, $HttpCode, $IsRetryable, $Type = null, $Resource = null, $Details = null)
    {
        $this->httpCode = $HttpCode;
        $this->isRetryable = $IsRetryable;
        $this->type = $Type;
        $this->resource = $Resource;
        $this->details = $Details;
        parent::__construct($Message, $Code);
    }
}
