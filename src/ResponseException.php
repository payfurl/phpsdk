<?php
namespace payFURL\Sdk;

/*
 * (c) payFURL
 */
final class ResponseException extends \Exception
{
    function __construct($Message, $HttpCode)
    {
        parent::__construct($Message, $HttpCode);
    }
}
