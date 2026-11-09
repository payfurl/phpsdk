<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `NetworkTokenSummary` (api/src/api/Models/NetworkTokenSummary.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class NetworkTokenSummary
{
    /**
     * @param array<string, string>|null $schemeTokenData
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $networkTokenId = null,
        public ?string $schemeTokenId = null,
        public ?string $providerId = null,
        public ?string $providerType = null,
        public ?string $status = null,
        public ?\DateTimeImmutable $dateAdded = null,
        public ?array $schemeTokenData = null,
        public ?NetworkTokenAuthenticationMethod $authenticationMethod = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            networkTokenId: Read::string($data, 'networkTokenId'),
            schemeTokenId: Read::string($data, 'schemeTokenId'),
            providerId: Read::string($data, 'providerId'),
            providerType: Read::string($data, 'providerType'),
            status: Read::string($data, 'status'),
            dateAdded: Read::date($data, 'dateAdded'),
            schemeTokenData: Read::stringMap($data, 'schemeTokenData'),
            authenticationMethod: Read::object($data, 'authenticationMethod', NetworkTokenAuthenticationMethod::class),
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
