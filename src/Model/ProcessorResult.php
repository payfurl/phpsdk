<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `ProcessorResult` (_shared/src/common/Models/ProcessorResult.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class ProcessorResult
{
    /**
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?string $authorizationCode = null,
        public ?string $responseCode = null,
        public ?string $responseText = null,
        public ?string $cvvResult = null,
        public ?string $avsStreetResult = null,
        public ?string $avsZipResult = null,
        public ?string $avsNameResult = null,
        public ?string $avsFullResult = null,
        public ?string $networkTransactionId = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            authorizationCode: Read::string($data, 'authorizationCode'),
            responseCode: Read::string($data, 'responseCode'),
            responseText: Read::string($data, 'responseText'),
            cvvResult: Read::string($data, 'cvvResult'),
            avsStreetResult: Read::string($data, 'avsStreetResult'),
            avsZipResult: Read::string($data, 'avsZipResult'),
            avsNameResult: Read::string($data, 'avsNameResult'),
            avsFullResult: Read::string($data, 'avsFullResult'),
            networkTransactionId: Read::string($data, 'networkTransactionId'),
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
