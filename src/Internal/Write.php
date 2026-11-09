<?php

declare(strict_types=1);

namespace payFURL\Sdk\Internal;

/**
 * Builds the JSON bodies and query strings of requests from the request models.
 *
 * @internal
 */
final class Write
{
    /**
     * Leaves out the fields that are null, so the API applies its own default. `false` and `0` are sent.
     *
     * @param array<string, mixed> $fields
     *
     * @return array<string, mixed>
     */
    public static function clean(array $fields): array
    {
        return array_filter($fields, static fn(mixed $value): bool => $value !== null);
    }

    /**
     * A JSON object even when empty: `[]` would be a JSON array, which the API's dictionaries reject.
     *
     * @param array<string, string>|null $map
     */
    public static function map(?array $map): ?object
    {
        return $map === null ? null : (object) $map;
    }

    public static function date(?\DateTimeInterface $date): ?string
    {
        return $date?->format(\DateTimeInterface::RFC3339_EXTENDED);
    }

    /**
     * @param list<object>|null $items models with a toArray(): array
     *
     * @return list<array<string, mixed>>|null
     */
    public static function objects(?array $items): ?array
    {
        if ($items === null) {
            return null;
        }

        return array_map(static function (object $item): array {
            /** @var callable(): array<string, mixed> $toArray */
            $toArray = [$item, 'toArray'];

            return $toArray();
        }, $items);
    }

    /**
     * @param list<\BackedEnum>|null $cases
     *
     * @return list<int|string>|null
     */
    public static function enums(?array $cases): ?array
    {
        return $cases === null ? null : array_map(static fn(\BackedEnum $case): int|string => $case->value, $cases);
    }

    /**
     * Query string parameters: nulls left out, the rest as the API's model binding reads them.
     *
     * @param array<string, scalar|\DateTimeInterface|\BackedEnum|null> $parameters
     *
     * @return array<string, string>
     */
    public static function query(array $parameters): array
    {
        $query = [];
        foreach ($parameters as $name => $value) {
            $query[$name] = match (true) {
                $value === null => null,
                is_bool($value) => $value ? 'true' : 'false',
                $value instanceof \DateTimeInterface => $value->format(\DateTimeInterface::RFC3339),
                $value instanceof \BackedEnum => (string) $value->value,
                default => (string) $value,
            };
        }

        return array_filter($query, static fn(?string $value): bool => $value !== null);
    }
}
