<?php

declare(strict_types=1);

namespace payFURL\Sdk\Model;

use payFURL\Sdk\Internal\Read;

/**
 * The API's `InfoProviders` (api/src/api/Models/InfoProviders.cs), in a response.
 *
 * Every property is null when the response leaves it out; `toArray()` returns the response as the API sent it.
 */
final readonly class InfoProviders
{
    /**
     * @param list<string>|null $paypalProviders
     * @param list<string>|null $payToProviders
     * @param list<PayToProviderOptions>|null $payToProvidersWithOptions
     * @param list<BnplOptions>|null $bnplProviders
     * @param list<string>|null $visaInstallmentsProviders
     * @param list<FraudOptions>|null $fraudProviders
     * @param list<ThreeDsOptions>|null $threeDsProviders
     * @param list<ThreeDsOptions>|null $allThreeDsProviders
     * @param list<GooglePayProviderInfo>|null $googlePayProviders
     * @param list<ApplePayProviderInfo>|null $applePayProviders
     * @param list<NetworkTokenProviderInfo>|null $networkTokenProviders
     * @param array<array-key, mixed> $raw
     */
    public function __construct(
        public ?array $paypalProviders = null,
        public ?bool $hasCardProviders = null,
        public ?bool $hasPaypalProviders = null,
        public ?string $required3dsProvider = null,
        public ?bool $hasPayToProviders = null,
        public ?array $payToProviders = null,
        public ?array $payToProvidersWithOptions = null,
        public ?bool $hasBnplProviders = null,
        public ?array $bnplProviders = null,
        public ?bool $hasVisaInstallmentsProviders = null,
        public ?array $visaInstallmentsProviders = null,
        public ?bool $hasFraudProviders = null,
        public ?array $fraudProviders = null,
        public ?bool $hasThreeDsProviders = null,
        public ?array $threeDsProviders = null,
        public ?array $allThreeDsProviders = null,
        public ?bool $hasGooglePayProviders = null,
        public ?array $googlePayProviders = null,
        public ?bool $hasApplePayProviders = null,
        public ?array $applePayProviders = null,
        public ?bool $clickToPayEnabled = null,
        public ?ClickToPayOptions $clickToPayOptions = null,
        public ?bool $hasNetworkTokenProviders = null,
        public ?array $networkTokenProviders = null,
        private array $raw = [],
    ) {}

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            paypalProviders: Read::strings($data, 'paypalProviders'),
            hasCardProviders: Read::bool($data, 'hasCardProviders'),
            hasPaypalProviders: Read::bool($data, 'hasPaypalProviders'),
            required3dsProvider: Read::string($data, 'required3dsProvider'),
            hasPayToProviders: Read::bool($data, 'hasPayToProviders'),
            payToProviders: Read::strings($data, 'payToProviders'),
            payToProvidersWithOptions: Read::objects($data, 'payToProvidersWithOptions', PayToProviderOptions::class),
            hasBnplProviders: Read::bool($data, 'hasBnplProviders'),
            bnplProviders: Read::objects($data, 'bnplProviders', BnplOptions::class),
            hasVisaInstallmentsProviders: Read::bool($data, 'hasVisaInstallmentsProviders'),
            visaInstallmentsProviders: Read::strings($data, 'visaInstallmentsProviders'),
            hasFraudProviders: Read::bool($data, 'hasFraudProviders'),
            fraudProviders: Read::objects($data, 'fraudProviders', FraudOptions::class),
            hasThreeDsProviders: Read::bool($data, 'hasThreeDsProviders'),
            threeDsProviders: Read::objects($data, 'threeDsProviders', ThreeDsOptions::class),
            allThreeDsProviders: Read::objects($data, 'allThreeDsProviders', ThreeDsOptions::class),
            hasGooglePayProviders: Read::bool($data, 'hasGooglePayProviders'),
            googlePayProviders: Read::objects($data, 'googlePayProviders', GooglePayProviderInfo::class),
            hasApplePayProviders: Read::bool($data, 'hasApplePayProviders'),
            applePayProviders: Read::objects($data, 'applePayProviders', ApplePayProviderInfo::class),
            clickToPayEnabled: Read::bool($data, 'clickToPayEnabled'),
            clickToPayOptions: Read::object($data, 'clickToPayOptions', ClickToPayOptions::class),
            hasNetworkTokenProviders: Read::bool($data, 'hasNetworkTokenProviders'),
            networkTokenProviders: Read::objects($data, 'networkTokenProviders', NetworkTokenProviderInfo::class),
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
