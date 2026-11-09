<?php

declare(strict_types=1);

namespace payFURL\Sdk\Resource;

use payFURL\Sdk\Internal\ApiClient;
use payFURL\Sdk\Internal\Write;
use payFURL\Sdk\Model\InfoProviders;

/**
 * What the account can take at checkout (InfoController). It takes either key: a client made with the public key
 * calls it too, to check that key.
 */
final class Info
{
    /** @internal */
    public function __construct(private readonly ApiClient $api) {}

    /**
     * `GET /info/providers`: the payment methods a checkout of this amount and currency can show.
     *
     * @param string|null $browserSdkVersion the version of the browser SDK (@payfurl/client) the checkout runs, such
     *                                       as `4.11.0`. The API lists PayPal only for version 4 or later, so leave
     *                                       it null only when the checkout shows no PayPal button.
     * @param string|null $cardProviderId the provider the checkout's cards go to, for its 3DS and network token
     *                                    options
     */
    public function providers(
        ?float $amount = null,
        ?string $currency = null,
        ?string $browserSdkVersion = null,
        ?string $cardProviderId = null,
    ): InfoProviders {
        return InfoProviders::fromArray($this->api->get(
            '/info/providers',
            Write::query(['amount' => $amount, 'currency' => $currency, 'cardProviderId' => $cardProviderId]),
            $browserSdkVersion === null ? [] : ['sdk-version' => $browserSdkVersion],
        ));
    }
}
