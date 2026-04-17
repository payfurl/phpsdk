<?php

namespace payFURL\Sdk;

require_once(__DIR__ . '/tools/HttpWrapper.php');

/**
 * @copyright PayFURL
 */
class Version
{
    /**
     * @throws ResponseException
     */
    public function Get()
    {
        return HttpWrapper::CallApi('/version', 'GET', '');
    }
}
