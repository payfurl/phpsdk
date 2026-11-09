<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `VisaInstallmentsTermsAndConditions` (_shared/src/api.core/DTO/VisaInstallmentsPlan.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class VisaInstallmentsTermsAndConditions
{
    /**
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $url = null,
        public ?int $version = null,
        public ?string $text = null,
        public ?string $languageCode = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            url: Read::string($data, 'url'),
            version: Read::int($data, 'version'),
            text: Read::string($data, 'text'),
            languageCode: Read::string($data, 'languageCode'),
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
