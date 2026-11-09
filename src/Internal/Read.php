<?php

declare(strict_types=1);

namespace payFURL\Sdk\Internal;

/**
 * Reads a decoded JSON response into the response models' typed properties. A missing key, or a value of the
 * wrong type, reads as null: the API adding or changing a field never breaks the SDK.
 *
 * @internal
 */
final class Read
{
    /**
     * @param array<array-key, mixed> $data
     */
    public static function string(array $data, string $key): ?string
    {
        $value = $data[$key] ?? null;

        return is_string($value) || is_int($value) || is_float($value) ? (string) $value : null;
    }

    /**
     * @param array<array-key, mixed> $data
     */
    public static function int(array $data, string $key): ?int
    {
        $value = $data[$key] ?? null;

        return is_int($value) || (is_numeric($value) && (int) $value == $value) ? (int) $value : null;
    }

    /**
     * @param array<array-key, mixed> $data
     */
    public static function float(array $data, string $key): ?float
    {
        $value = $data[$key] ?? null;

        return is_numeric($value) ? (float) $value : null;
    }

    /**
     * @param array<array-key, mixed> $data
     */
    public static function bool(array $data, string $key): ?bool
    {
        $value = $data[$key] ?? null;

        return is_bool($value) ? $value : null;
    }

    /**
     * @param array<array-key, mixed> $data
     */
    public static function date(array $data, string $key): ?\DateTimeImmutable
    {
        $value = $data[$key] ?? null;
        if (!is_string($value) || $value === '') {
            return null;
        }
        try {
            return new \DateTimeImmutable($value);
        } catch (\Exception) {
            return null;
        }
    }

    /**
     * @param array<array-key, mixed> $data
     *
     * @return array<string, string>|null
     */
    public static function stringMap(array $data, string $key): ?array
    {
        $value = $data[$key] ?? null;
        if (!is_array($value)) {
            return null;
        }
        $map = [];
        foreach ($value as $name => $item) {
            if (is_scalar($item)) {
                $map[(string) $name] = (string) $item;
            }
        }

        return $map;
    }

    /**
     * @param array<array-key, mixed> $data
     *
     * @return list<string>|null
     */
    public static function strings(array $data, string $key): ?array
    {
        $value = $data[$key] ?? null;

        return is_array($value) ? array_values(array_map('strval', array_filter($value, 'is_scalar'))) : null;
    }

    /**
     * @template T of object
     *
     * @param array<array-key, mixed> $data
     * @param class-string<T> $class a model with a static fromArray(array): self
     *
     * @return T|null
     */
    public static function object(array $data, string $key, string $class): ?object
    {
        $value = $data[$key] ?? null;

        return is_array($value) ? self::build($class, $value) : null;
    }

    /**
     * @template T of object
     *
     * @param array<array-key, mixed> $data
     * @param class-string<T> $class a model with a static fromArray(array): self
     *
     * @return list<T>|null
     */
    public static function objects(array $data, string $key, string $class): ?array
    {
        $value = $data[$key] ?? null;

        return is_array($value) ? self::list($value, $class) : null;
    }

    /**
     * A JSON array of objects, such as the body of GET /customer/{id}/payment_method.
     *
     * @template T of object
     *
     * @param array<array-key, mixed> $items
     * @param class-string<T> $class a model with a static fromArray(array): self
     *
     * @return list<T>
     */
    public static function list(array $items, string $class): array
    {
        $list = [];
        foreach ($items as $item) {
            if (is_array($item)) {
                $list[] = self::build($class, $item);
            }
        }

        return $list;
    }

    /**
     * An enum case, or null for a value this SDK version does not know (the raw value stays in `toArray()`).
     *
     * @template T of \BackedEnum
     *
     * @param array<array-key, mixed> $data
     * @param class-string<T> $enum
     *
     * @return T|null
     */
    public static function enum(array $data, string $key, string $enum): ?\BackedEnum
    {
        $value = $data[$key] ?? null;

        return is_string($value) || is_int($value) ? $enum::tryFrom($value) : null;
    }

    /**
     * @template T of \BackedEnum
     *
     * @param array<array-key, mixed> $data
     * @param class-string<T> $enum
     *
     * @return list<T>|null
     */
    public static function enums(array $data, string $key, string $enum): ?array
    {
        $value = $data[$key] ?? null;
        if (!is_array($value)) {
            return null;
        }
        $cases = [];
        foreach ($value as $item) {
            $case = is_string($item) || is_int($item) ? $enum::tryFrom($item) : null;
            if ($case !== null) {
                $cases[] = $case;
            }
        }

        return $cases;
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $class
     * @param array<array-key, mixed> $data
     *
     * @return T
     */
    private static function build(string $class, array $data): object
    {
        /** @var callable(array<array-key, mixed>): T $fromArray */
        $fromArray = [$class, 'fromArray'];

        return $fromArray($data);
    }
}
