<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `NewVault` (api/src/api/Models/NewVault.cs), in a request.
 */
final readonly class NewVault
{
    public function __construct(
        public string $cardNumber,
        public ?string $ccv = null,
        public ?\DateTimeInterface $expireDate = null,
        public ?int $expireSeconds = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'cardNumber' => $this->cardNumber,
            'ccv' => $this->ccv,
            'expireDate' => Write::date($this->expireDate),
            'expireSeconds' => $this->expireSeconds,
        ]);
    }
}
