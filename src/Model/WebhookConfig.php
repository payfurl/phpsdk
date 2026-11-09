<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;
use payFURL\Sdk\Internal\Write;

/**
 * The API's `WebhookConfig` (api/src/api/Models/WebhookConfig.cs), in a request and response.
 */
final readonly class WebhookConfig
{
    public function __construct(
        public ?string $url = null,
        public ?string $authorization = null,
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            url: Read::string($data, 'url'),
            authorization: Read::string($data, 'authorization'),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'url' => $this->url,
            'authorization' => $this->authorization,
        ]);
    }
}
