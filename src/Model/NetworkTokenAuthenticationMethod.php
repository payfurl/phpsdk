<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `NetworkTokenAuthenticationMethod` (_shared/src/api.core/Data/NetworkTokenAuthenticationMethod.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class NetworkTokenAuthenticationMethod
{
    /**
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $authenticationMethodType = null,
        public ?string $authenticationSubject = null,
        public ?UriData $uriData = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            authenticationMethodType: Read::string($data, 'authenticationMethodType'),
            authenticationSubject: Read::string($data, 'authenticationSubject'),
            uriData: Read::object($data, 'uriData', UriData::class),
            raw: $data,
        );
    }

    /**
     * @return array<array-key, mixed>
     */
    public function toArray(): array
    {
        return $this->raw;
    }
}
