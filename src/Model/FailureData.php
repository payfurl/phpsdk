<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `FailureData` (_shared/src/api.core/Data/FailureData.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class FailureData
{
    /**
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $providerId = null,
        public ?\DateTimeImmutable $dateAttempted = null,
        public ?string $errorCode = null,
        public ?string $errorMessage = null,
        public ?string $gatewayCode = null,
        public ?string $gatewayMessage = null,
        public ?ProcessorResult $processorResult = null,
        public ?bool $isRetryable = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            providerId: Read::string($data, 'providerId'),
            dateAttempted: Read::date($data, 'dateAttempted'),
            errorCode: Read::string($data, 'errorCode'),
            errorMessage: Read::string($data, 'errorMessage'),
            gatewayCode: Read::string($data, 'gatewayCode'),
            gatewayMessage: Read::string($data, 'gatewayMessage'),
            processorResult: Read::object($data, 'processorResult', ProcessorResult::class),
            isRetryable: Read::bool($data, 'isRetryable'),
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
