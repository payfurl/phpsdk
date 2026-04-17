<?php

namespace payFURL\Sdk;

require_once(__DIR__ . '/tools/HttpWrapper.php');

/**
 * @copyright PayFURL
 */
class Root
{
    /**
     * @throws ResponseException
     */
    public function Get()
    {
        return HttpWrapper::CallApi('/', 'GET', '');
    }
}
