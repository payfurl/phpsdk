<?php
namespace payFURL\Sdk;

/*
 * (c) payFurl
 */
final class ResponseException extends \Exception
{
    function __construct($Message, $HttpCode)
    {
        parent::__construct($Message, $HttpCode);
    }
}
