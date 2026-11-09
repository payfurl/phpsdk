<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `NewProvider` (api/src/api/Models/NewProvider.cs), in a request.
 */
final readonly class NewProvider
{
    /**
     * @param array<string, string> $authenticationParameters
     * @param array<string, string>|null $additionalParameters
     */
    public function __construct(
        public string $type,
        public string $name,
        public string $environment,
        public array $authenticationParameters,
        public string $currency,
        public ?array $additionalParameters = null,
        public ?string $providerCountry = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'type' => $this->type,
            'name' => $this->name,
            'environment' => $this->environment,
            'authenticationParameters' => Write::map($this->authenticationParameters),
            'currency' => $this->currency,
            'additionalParameters' => Write::map($this->additionalParameters),
            'providerCountry' => $this->providerCountry,
        ]);
    }
}
