<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `UpdateProvider` (api/src/api/Models/UpdateProvider.cs), in a request.
 */
final readonly class UpdateProvider
{
    /**
     * @param array<string, string>|null $authenticationParameters
     * @param array<string, string>|null $additionalParameters
     */
    public function __construct(
        public ?string $name = null,
        public ?array $authenticationParameters = null,
        public ?array $additionalParameters = null,
        public ?string $providerCountry = null,
        public ?string $currency = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'name' => $this->name,
            'authenticationParameters' => Write::map($this->authenticationParameters),
            'additionalParameters' => Write::map($this->additionalParameters),
            'providerCountry' => $this->providerCountry,
            'currency' => $this->currency,
        ]);
    }
}
