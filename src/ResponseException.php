<?php
namespace payFURL\Sdk;

/**
 * @copyright PayFURL
 */
final class ResponseException extends \Exception
{
    function __construct($Message, $HttpCode)
    {
        parent::__construct($Message, $HttpCode);
    }
}
