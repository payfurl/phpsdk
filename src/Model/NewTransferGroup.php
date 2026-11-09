<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Write;

/**
 * The API's `NewTransferGroup` (api/src/api/Models/NewTransferGroup.cs), in a request.
 */
final readonly class NewTransferGroup
{
    /**
     * @param string|null $groupReference a reference for the group, unique on the account; null leaves it out, as the
     *                                    API allows (it is only checked for duplicates when set)
     * @param list<NewTransfer> $transfers
     */
    public function __construct(
        public string $providerId,
        public ?string $groupReference,
        public array $transfers,
        public ?string $chargeId = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return Write::clean([
            'providerId' => $this->providerId,
            'groupReference' => $this->groupReference,
            'transfers' => Write::objects($this->transfers),
            'chargeId' => $this->chargeId,
        ]);
    }
}
