<?php

declare(strict_types=1);

namespace payFURL\Sdk\Resource;

use payFURL\Sdk\Internal\ApiClient;
use payFURL\Sdk\Model\TokenData;
use payFURL\Sdk\Model\TokenList;
use payFURL\Sdk\Model\TokenSearch;

/**
 * Tokens from the browser SDK and hosted pages (TokenController). Tokens are made in the browser with the public
 * key; the server reads them.
 */
final class Tokens
{
    /** @internal */
    public function __construct(private readonly ApiClient $api) {}

    /** `GET /token/{tokenId}` */
    public function get(string $tokenId): TokenData
    {
        return TokenData::fromArray($this->api->get('/token/' . ApiClient::id($tokenId, 'tokenId')));
    }

    /** `GET /token` */
    public function search(TokenSearch $search = new TokenSearch()): TokenList
    {
        return TokenList::fromArray($this->api->get('/token', $search->toQuery()));
    }
}
