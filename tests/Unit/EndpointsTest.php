<?php

declare(strict_types=1);

namespace payFURL\Sdk\Tests\Unit;

use payFURL\Sdk\ClientOptions;
use payFURL\Sdk\Environment;
use payFURL\Sdk\Http\FakeTransport;
use payFURL\Sdk\Model\Address;
use payFURL\Sdk\Model\ApplicationVersion;
use payFURL\Sdk\Model\BatchData;
use payFURL\Sdk\Model\BatchList;
use payFURL\Sdk\Model\BatchSearch;
use payFURL\Sdk\Model\BatchStatus;
use payFURL\Sdk\Model\CardRequestInformation;
use payFURL\Sdk\Model\ChargeData;
use payFURL\Sdk\Model\ChargeList;
use payFURL\Sdk\Model\ChargeSearch;
use payFURL\Sdk\Model\CheckoutTransfer;
use payFURL\Sdk\Model\CreatePaymentLink;
use payFURL\Sdk\Model\CustomerData;
use payFURL\Sdk\Model\CustomerList;
use payFURL\Sdk\Model\CustomerSearch;
use payFURL\Sdk\Model\Dispute;
use payFURL\Sdk\Model\DisputeList;
use payFURL\Sdk\Model\DisputeSearch;
use payFURL\Sdk\Model\InfoProviders;
use payFURL\Sdk\Model\Initiator;
use payFURL\Sdk\Model\NewBankPayment;
use payFURL\Sdk\Model\NewChargeBankPayment;
use payFURL\Sdk\Model\NewChargeCard;
use payFURL\Sdk\Model\NewChargeCardLeastCost;
use payFURL\Sdk\Model\NewChargeCustomer;
use payFURL\Sdk\Model\NewChargeNetworkToken;
use payFURL\Sdk\Model\NewChargePaymentMethod;
use payFURL\Sdk\Model\NewChargeToken;
use payFURL\Sdk\Model\NewCustomerBankPayment;
use payFURL\Sdk\Model\NewCustomerCard;
use payFURL\Sdk\Model\NewCustomerPayTo;
use payFURL\Sdk\Model\NewCustomerProviderToken;
use payFURL\Sdk\Model\NewCustomerToken;
use payFURL\Sdk\Model\NewPaymentMethodBankPayment;
use payFURL\Sdk\Model\NewPaymentMethodCard;
use payFURL\Sdk\Model\NewPaymentMethodToken;
use payFURL\Sdk\Model\NewPaymentMethodVault;
use payFURL\Sdk\Model\NewPayToAgreement;
use payFURL\Sdk\Model\NewProvider;
use payFURL\Sdk\Model\NewProviderToken;
use payFURL\Sdk\Model\NewTransactionPaymentMethod;
use payFURL\Sdk\Model\NewTransfer;
use payFURL\Sdk\Model\NewTransferGroup;
use payFURL\Sdk\Model\NewVault;
use payFURL\Sdk\Model\NewWebhookSubscription;
use payFURL\Sdk\Model\PayIdDetails;
use payFURL\Sdk\Model\PaymentLink;
use payFURL\Sdk\Model\PaymentLinkList;
use payFURL\Sdk\Model\PaymentLinkSearch;
use payFURL\Sdk\Model\PaymentMethodData;
use payFURL\Sdk\Model\PaymentMethodList;
use payFURL\Sdk\Model\PaymentMethodSearch;
use payFURL\Sdk\Model\PaymentType;
use payFURL\Sdk\Model\Provider;
use payFURL\Sdk\Model\RootStatus;
use payFURL\Sdk\Model\SingleUseToken;
use payFURL\Sdk\Model\Subscription;
use payFURL\Sdk\Model\SubscriptionCreate;
use payFURL\Sdk\Model\SubscriptionEnd;
use payFURL\Sdk\Model\SubscriptionInterval;
use payFURL\Sdk\Model\SubscriptionList;
use payFURL\Sdk\Model\SubscriptionSearch;
use payFURL\Sdk\Model\SubscriptionStatus;
use payFURL\Sdk\Model\SubscriptionUpdate;
use payFURL\Sdk\Model\TokenData;
use payFURL\Sdk\Model\TokenList;
use payFURL\Sdk\Model\TokenSearch;
use payFURL\Sdk\Model\TransactionStatus;
use payFURL\Sdk\Model\TransferData;
use payFURL\Sdk\Model\TransferList;
use payFURL\Sdk\Model\TransferSearch;
use payFURL\Sdk\Model\UpdateCustomer;
use payFURL\Sdk\Model\UpdatePaymentMethod;
use payFURL\Sdk\Model\UpdatePaymentMethodCardRequestInformation;
use payFURL\Sdk\Model\UpdateProvider;
use payFURL\Sdk\Model\VaultData;
use payFURL\Sdk\Model\VaultDataWithPCI;
use payFURL\Sdk\Model\WebhookConfig;
use payFURL\Sdk\Model\WebhookSubscription;
use payFURL\Sdk\Model\WebhookSubscriptionList;
use payFURL\Sdk\Model\WebhookSubscriptionSearch;
use payFURL\Sdk\Model\WebhookType;
use payFURL\Sdk\PayfurlClient;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Every endpoint: the request the SDK sends (method, path, query, body) and the model it returns.
 */
final class EndpointsTest extends TestCase
{
    private const CARD = ['cardNumber' => '4111111111111111', 'expiryDate' => '12/30', 'ccv' => '123'];
    private const BANK = ['bankCode' => '092-000', 'accountNumber' => '12345', 'accountName' => 'Payfurl1'];
    private const PAYTO = ['payerName' => 'Jo', 'payerPayIdDetails' => ['payId' => 'jo@example.com', 'payIdType' => 'EMAIL'],
        'description' => 'Rent', 'maximumAmount' => 500, 'providerId' => 'p1'];

    /**
     * @param \Closure(PayfurlClient): mixed $call
     * @param array<string, string> $query
     * @param array<string, mixed>|null $body
     * @param class-string|'list' $returns
     * @param array<array-key, mixed> $response
     */
    #[DataProvider('endpoints')]
    public function testEndpoint(\Closure $call, string $method, string $path, array $query, ?array $body, string $returns, array $response = ['id' => 'x']): void
    {
        $transport = (new FakeTransport())->queue(200, $response);
        $client = new PayfurlClient('sectest0123456789-au', Environment::Sandbox, new ClientOptions(transport: $transport));

        $result = $call($client);

        $request = $transport->lastRequest();
        self::assertSame($method, $request->method);
        $expectedUrl = 'https://sandbox-api-au.payfurl.com' . $path . ($query === [] ? '' : '?' . http_build_query($query, '', '&', PHP_QUERY_RFC3986));
        self::assertSame($expectedUrl, $request->url);
        if ($body === null) {
            self::assertNull($request->body);
            self::assertArrayNotHasKey('Content-Type', $request->headers);
        } else {
            self::assertSame(self::sorted($body), self::sorted($transport->lastBody()));
            self::assertSame('application/json', $request->headers['Content-Type']);
        }
        self::assertSame('sectest0123456789-au', $request->headers['x-secretkey']);
        if ($returns === 'list') {
            self::assertIsArray($result);
        } else {
            self::assertInstanceOf($returns, $result);
        }
    }

    /**
     * Object keys in order, so the comparison ignores the order of a body's fields but not their types.
     *
     * @param array<array-key, mixed> $value
     *
     * @return array<array-key, mixed>
     */
    private static function sorted(array $value): array
    {
        if (!array_is_list($value)) {
            ksort($value);
        }

        return array_map(static fn(mixed $item): mixed => is_array($item) ? self::sorted($item) : $item, $value);
    }

    /**
     * @return iterable<string, array{0: \Closure(PayfurlClient): mixed, 1: string, 2: string, 3: array<string, string>, 4: array<string, mixed>|null, 5: string, 6?: array<array-key, mixed>}>
     */
    public static function endpoints(): iterable
    {
        $date = new \DateTimeImmutable('2026-11-01T10:00:00+00:00');
        $card = new CardRequestInformation(cardNumber: '4111111111111111', expiryDate: '12/30', ccv: '123');
        $bank = new NewBankPayment(bankCode: '092-000', accountNumber: '12345', accountName: 'Payfurl1');
        $payTo = new NewPayToAgreement(
            payerName: 'Jo',
            description: 'Rent',
            maximumAmount: 500,
            providerId: 'p1',
            payerPayIdDetails: new PayIdDetails(payId: 'jo@example.com', payIdType: 'EMAIL'),
        );

        // Charges
        yield 'charges createWithCard' => [
            fn(PayfurlClient $c) => $c->charges->createWithCard(new NewChargeCard(
                amount: 20.5,
                providerId: 'p1',
                paymentInformation: $card,
                currency: 'AUD',
                capture: false,
                initiator: Initiator::Merchant,
                metadata: ['order' => '42'],
            )),
            'POST', '/charge/card', [],
            ['amount' => 20.5, 'providerId' => 'p1', 'paymentInformation' => self::CARD, 'currency' => 'AUD', 'capture' => false,
                'initiator' => 'MERCHANT', 'metadata' => ['order' => '42']],
            ChargeData::class,
        ];
        yield 'charges createWithCardLeastCost' => [
            fn(PayfurlClient $c) => $c->charges->createWithCardLeastCost(new NewChargeCardLeastCost(amount: 10.0, paymentInformation: $card)),
            'POST', '/charge/card/least_cost', [], ['amount' => 10.0, 'paymentInformation' => self::CARD], ChargeData::class,
        ];
        yield 'charges createWithCustomer' => [
            fn(PayfurlClient $c) => $c->charges->createWithCustomer(new NewChargeCustomer(amount: 10.0, customerId: 'cu1', recurring: true)),
            'POST', '/charge/customer', [], ['amount' => 10.0, 'customerId' => 'cu1', 'recurring' => true], ChargeData::class,
        ];
        yield 'charges createWithPaymentMethod' => [
            fn(PayfurlClient $c) => $c->charges->createWithPaymentMethod(new NewChargePaymentMethod(amount: 10.0, paymentMethodId: 'pm1', reference: 'r1')),
            'POST', '/charge/payment_method', [], ['amount' => 10.0, 'paymentMethodId' => 'pm1', 'reference' => 'r1'], ChargeData::class,
        ];
        yield 'charges createWithToken' => [
            fn(PayfurlClient $c) => $c->charges->createWithToken(new NewChargeToken(
                amount: 10.0,
                token: 't1',
                transfer: new CheckoutTransfer(amount: 2.0, account: 'acc'),
                address: new Address(line1: '1 George St', country: 'AU'),
                webhook: new WebhookConfig(url: 'https://shop.example/hook'),
            )),
            'POST', '/charge/token', [],
            ['amount' => 10.0, 'token' => 't1', 'transfer' => ['amount' => 2.0, 'account' => 'acc'],
                'address' => ['line1' => '1 George St', 'country' => 'AU'], 'webhook' => ['url' => 'https://shop.example/hook']],
            ChargeData::class,
        ];
        yield 'charges createWithBankAccount' => [
            fn(PayfurlClient $c) => $c->charges->createWithBankAccount(new NewChargeBankPayment(amount: 10.0, providerId: 'p1', bankPaymentInformation: $bank)),
            'POST', '/charge/bank_account', [], ['amount' => 10.0, 'providerId' => 'p1', 'bankPaymentInformation' => self::BANK], ChargeData::class,
        ];
        yield 'charges createWithNetworkToken' => [
            fn(PayfurlClient $c) => $c->charges->createWithNetworkToken(new NewChargeNetworkToken(amount: 10.0, providerId: 'p1', networkTokenId: 'nt1')),
            'POST', '/charge/network_token', [], ['amount' => 10.0, 'providerId' => 'p1', 'networkTokenId' => 'nt1'], ChargeData::class,
        ];
        yield 'charges get' => [fn(PayfurlClient $c) => $c->charges->get('ch/1'), 'GET', '/charge/ch%2F1', [], null, ChargeData::class];
        yield 'charges search' => [
            fn(PayfurlClient $c) => $c->charges->search(new ChargeSearch(
                status: TransactionStatus::Success,
                paymentType: PaymentType::Card,
                amountGreaterThan: 5.5,
                addedAfter: $date,
                limit: 10,
            )),
            'GET', '/charge', ['amountGreaterThan' => '5.5', 'status' => 'SUCCESS', 'addedAfter' => '2026-11-01T10:00:00+00:00',
                'paymentType' => 'CARD', 'limit' => '10'], null, ChargeList::class,
        ];
        yield 'charges search without filters' => [fn(PayfurlClient $c) => $c->charges->search(), 'GET', '/charge', [], null, ChargeList::class];
        yield 'charges refund in part' => [
            fn(PayfurlClient $c) => $c->charges->refund('ch1', 5.0, 'damaged'),
            'DELETE', '/charge/ch1', ['amount' => '5', 'comment' => 'damaged'], null, ChargeData::class,
        ];
        yield 'charges refund in full' => [fn(PayfurlClient $c) => $c->charges->refund('ch1'), 'DELETE', '/charge/ch1', [], null, ChargeData::class];
        yield 'charges capture' => [fn(PayfurlClient $c) => $c->charges->capture('ch1', 7.25), 'POST', '/charge/ch1/capture', [], ['amount' => 7.25], ChargeData::class];
        yield 'charges capture in full' => [fn(PayfurlClient $c) => $c->charges->capture('ch1'), 'POST', '/charge/ch1/capture', [], [], ChargeData::class];
        yield 'charges void' => [fn(PayfurlClient $c) => $c->charges->void('ch1'), 'DELETE', '/charge/ch1/void', [], null, ChargeData::class];

        // Customers
        yield 'customers createWithCard' => [
            fn(PayfurlClient $c) => $c->customers->createWithCard(new NewCustomerCard(
                providerId: 'p1',
                paymentInformation: $card,
                email: 'jo@example.com',
                vaultCard: false,
                vaultExpireDate: $date,
            )),
            'POST', '/customer/card', [],
            ['providerId' => 'p1', 'paymentInformation' => self::CARD, 'email' => 'jo@example.com', 'vaultCard' => false,
                'vaultExpireDate' => '2026-11-01T10:00:00.000+00:00'],
            CustomerData::class,
        ];
        yield 'customers createWithToken' => [
            fn(PayfurlClient $c) => $c->customers->createWithToken(new NewCustomerToken(token: 't1', firstName: 'Jo')),
            'POST', '/customer/token', [], ['token' => 't1', 'firstName' => 'Jo'], CustomerData::class,
        ];
        yield 'customers createWithPayTo' => [
            fn(PayfurlClient $c) => $c->customers->createWithPayTo(new NewCustomerPayTo(payToAgreement: $payTo)),
            'POST', '/customer/payto', [], ['payToAgreement' => self::PAYTO], CustomerData::class,
        ];
        yield 'customers createWithProviderToken' => [
            fn(PayfurlClient $c) => $c->customers->createWithProviderToken(new NewCustomerProviderToken(providerId: 'p1', providerToken: 'cus_1', type: PaymentType::Card)),
            'POST', '/customer/provider_token', [], ['providerId' => 'p1', 'providerToken' => 'cus_1', 'type' => 'CARD'], CustomerData::class,
        ];
        yield 'customers createWithSingleUseToken' => [
            fn(PayfurlClient $c) => $c->customers->createWithSingleUseToken(new SingleUseToken(providerId: 'p1', providerToken: 'tok_1')),
            'POST', '/customer/provider_single_use_token', [], ['providerId' => 'p1', 'providerToken' => 'tok_1'], CustomerData::class,
        ];
        yield 'customers createWithBankAccount' => [
            fn(PayfurlClient $c) => $c->customers->createWithBankAccount(new NewCustomerBankPayment(providerId: 'p1', bankPaymentInformation: $bank)),
            'POST', '/customer/bank_account', [], ['providerId' => 'p1', 'bankPaymentInformation' => self::BANK], CustomerData::class,
        ];
        yield 'customers get' => [fn(PayfurlClient $c) => $c->customers->get('cu1'), 'GET', '/customer/cu1', [], null, CustomerData::class];
        yield 'customers search' => [
            fn(PayfurlClient $c) => $c->customers->search(new CustomerSearch(email: 'jo@example.com', includeRemoved: false, skip: 0)),
            'GET', '/customer', ['email' => 'jo@example.com', 'includeRemoved' => 'false', 'skip' => '0'], null, CustomerList::class,
        ];
        yield 'customers update' => [
            fn(PayfurlClient $c) => $c->customers->update('cu1', new UpdateCustomer(phone: '+61400000000', defaultPaymentMethodId: 'pm1')),
            'PUT', '/customer/cu1', [], ['phone' => '+61400000000', 'defaultPaymentMethodId' => 'pm1'], CustomerData::class,
        ];
        yield 'customers delete' => [fn(PayfurlClient $c) => $c->customers->delete('cu1'), 'DELETE', '/customer/cu1', [], null, CustomerData::class];
        yield 'customers delete at the gateway' => [
            fn(PayfurlClient $c) => $c->customers->delete('cu1', gatewayDelete: true), 'DELETE', '/customer/cu1', ['gatewayDelete' => 'true'], null, CustomerData::class,
        ];
        yield 'customers paymentMethods' => [
            fn(PayfurlClient $c) => $c->customers->paymentMethods('cu1'), 'GET', '/customer/cu1/payment_method', [], null, 'list',
            [['paymentMethodId' => 'pm1'], ['paymentMethodId' => 'pm2']],
        ];
        yield 'customers addPaymentMethodWithCard' => [
            fn(PayfurlClient $c) => $c->customers->addPaymentMethodWithCard('cu1', new NewPaymentMethodCard(providerId: 'p1', paymentInformation: $card, setDefault: true)),
            'POST', '/customer/cu1/payment_method/card', [], ['providerId' => 'p1', 'paymentInformation' => self::CARD, 'setDefault' => true], PaymentMethodData::class,
        ];
        yield 'customers addPaymentMethodWithToken' => [
            fn(PayfurlClient $c) => $c->customers->addPaymentMethodWithToken('cu1', new NewPaymentMethodToken(token: 't1')),
            'POST', '/customer/cu1/payment_method/token', [], ['token' => 't1'], PaymentMethodData::class,
        ];
        yield 'customers addPaymentMethodWithPayTo' => [
            fn(PayfurlClient $c) => $c->customers->addPaymentMethodWithPayTo('cu1', $payTo),
            'POST', '/customer/cu1/payment_method/payto', [], self::PAYTO, PaymentMethodData::class,
        ];
        yield 'customers addPaymentMethodWithBankAccount' => [
            fn(PayfurlClient $c) => $c->customers->addPaymentMethodWithBankAccount('cu1', new NewPaymentMethodBankPayment(providerId: 'p1', bankPaymentInformation: $bank)),
            'POST', '/customer/cu1/bank_account', [], ['providerId' => 'p1', 'bankPaymentInformation' => self::BANK], PaymentMethodData::class,
        ];

        // Payment methods
        yield 'paymentMethods createWithCard' => [
            fn(PayfurlClient $c) => $c->paymentMethods->createWithCard(new NewPaymentMethodCard(providerId: 'p1', paymentInformation: $card)),
            'POST', '/payment_method/card', [], ['providerId' => 'p1', 'paymentInformation' => self::CARD], PaymentMethodData::class,
        ];
        yield 'paymentMethods createWithVault' => [
            fn(PayfurlClient $c) => $c->paymentMethods->createWithVault(new NewPaymentMethodVault(providerId: 'p2', paymentMethodId: 'pm1')),
            'POST', '/payment_method/vault', [], ['providerId' => 'p2', 'paymentMethodId' => 'pm1'], PaymentMethodData::class,
        ];
        yield 'paymentMethods createWithPayTo' => [
            fn(PayfurlClient $c) => $c->paymentMethods->createWithPayTo($payTo), 'POST', '/payment_method/payto', [], self::PAYTO, PaymentMethodData::class,
        ];
        yield 'paymentMethods createWithToken' => [
            fn(PayfurlClient $c) => $c->paymentMethods->createWithToken(new NewPaymentMethodToken(token: 't1', metadata: [])),
            'POST', '/payment_method/token', [], ['token' => 't1', 'metadata' => []], PaymentMethodData::class,
        ];
        yield 'paymentMethods createWithSingleUseToken' => [
            fn(PayfurlClient $c) => $c->paymentMethods->createWithSingleUseToken(new SingleUseToken(providerId: 'p1', providerToken: 'tok_1')),
            'POST', '/payment_method/provider_single_use_token', [], ['providerId' => 'p1', 'providerToken' => 'tok_1'], PaymentMethodData::class,
        ];
        yield 'paymentMethods createWithProviderToken' => [
            fn(PayfurlClient $c) => $c->paymentMethods->createWithProviderToken(new NewProviderToken(providerId: 'p1', providerToken: 'pm_1', verify: false)),
            'POST', '/payment_method/provider_token', [], ['providerId' => 'p1', 'providerToken' => 'pm_1', 'verify' => false], PaymentMethodData::class,
        ];
        yield 'paymentMethods createWithBankAccount' => [
            fn(PayfurlClient $c) => $c->paymentMethods->createWithBankAccount(new NewPaymentMethodBankPayment(providerId: 'p1', bankPaymentInformation: $bank)),
            'POST', '/payment_method/bank_account', [], ['providerId' => 'p1', 'bankPaymentInformation' => self::BANK], PaymentMethodData::class,
        ];
        yield 'paymentMethods get' => [fn(PayfurlClient $c) => $c->paymentMethods->get('pm1'), 'GET', '/payment_method/pm1', [], null, PaymentMethodData::class];
        yield 'paymentMethods search' => [
            fn(PayfurlClient $c) => $c->paymentMethods->search(new PaymentMethodSearch(customerId: 'cu1', paymentType: PaymentType::PayTo)),
            'GET', '/payment_method', ['customerId' => 'cu1', 'paymentType' => 'PAYTO'], null, PaymentMethodList::class,
        ];
        yield 'paymentMethods update' => [
            fn(PayfurlClient $c) => $c->paymentMethods->update('pm1', new UpdatePaymentMethod(card: new UpdatePaymentMethodCardRequestInformation(expiryDate: '01/31'))),
            'PUT', '/payment_method/pm1', [], ['card' => ['expiryDate' => '01/31']], PaymentMethodData::class,
        ];
        yield 'paymentMethods delete' => [
            fn(PayfurlClient $c) => $c->paymentMethods->delete('pm1', gatewayDelete: false), 'DELETE', '/payment_method/pm1', ['gatewayDelete' => 'false'], null, PaymentMethodData::class,
        ];

        // Providers
        yield 'providers create' => [
            fn(PayfurlClient $c) => $c->providers->create(new NewProvider(
                type: 'dummy',
                name: 'Dummy',
                environment: 'SANDBOX',
                currency: 'AUD',
                authenticationParameters: ['FailureAmount' => '13'],
            )),
            'POST', '/provider', [],
            ['type' => 'dummy', 'name' => 'Dummy', 'environment' => 'SANDBOX', 'currency' => 'AUD', 'authenticationParameters' => ['FailureAmount' => '13']],
            Provider::class,
        ];
        yield 'providers update' => [
            fn(PayfurlClient $c) => $c->providers->update('p1', new UpdateProvider(name: 'Renamed')), 'PUT', '/provider/p1', [], ['name' => 'Renamed'], Provider::class,
        ];
        yield 'providers delete' => [fn(PayfurlClient $c) => $c->providers->delete('p1'), 'DELETE', '/provider/p1', [], null, Provider::class];

        // Subscriptions
        yield 'subscriptions create' => [
            fn(PayfurlClient $c) => $c->subscriptions->create(new SubscriptionCreate(
                amount: 9.95,
                paymentMethodId: 'pm1',
                currency: 'AUD',
                interval: SubscriptionInterval::Month,
                frequency: 1,
                startDate: $date,
                endAfter: new SubscriptionEnd(count: 12),
            )),
            'POST', '/subscription/payment_method', [],
            ['amount' => 9.95, 'paymentMethodId' => 'pm1', 'currency' => 'AUD', 'interval' => 'Month', 'frequency' => 1,
                'startDate' => '2026-11-01T10:00:00.000+00:00', 'endAfter' => ['count' => 12]],
            Subscription::class,
        ];
        yield 'subscriptions get' => [fn(PayfurlClient $c) => $c->subscriptions->get('s1'), 'GET', '/subscription/s1', [], null, Subscription::class];
        yield 'subscriptions search' => [
            fn(PayfurlClient $c) => $c->subscriptions->search(new SubscriptionSearch(status: SubscriptionStatus::Active)),
            'GET', '/subscription', ['status' => 'Active'], null, SubscriptionList::class,
        ];
        yield 'subscriptions update' => [
            fn(PayfurlClient $c) => $c->subscriptions->update('s1', new SubscriptionUpdate(amount: 12.0, currency: 'AUD', interval: SubscriptionInterval::Week, frequency: 2)),
            'PUT', '/subscription/s1', [], ['amount' => 12.0, 'currency' => 'AUD', 'interval' => 'Week', 'frequency' => 2], Subscription::class,
        ];
        yield 'subscriptions updateStatus' => [
            fn(PayfurlClient $c) => $c->subscriptions->updateStatus('s1', SubscriptionStatus::Suspended),
            'PUT', '/subscription/s1/status', [], ['status' => 'Suspended'], Subscription::class,
        ];
        yield 'subscriptions delete' => [fn(PayfurlClient $c) => $c->subscriptions->delete('s1'), 'DELETE', '/subscription/s1', [], null, Subscription::class];

        // Batches
        yield 'batches createWithPaymentMethods' => [
            fn(PayfurlClient $c) => $c->batches->createWithPaymentMethods(new NewTransactionPaymentMethod(count: 1, description: 'Nov', batch: "PaymentMethodId,Amount\npm1,10")),
            'POST', '/batch/transaction/payment_method', [], ['count' => 1, 'description' => 'Nov', 'batch' => "PaymentMethodId,Amount\npm1,10"], BatchStatus::class,
        ];
        yield 'batches get' => [fn(PayfurlClient $c) => $c->batches->get('b1'), 'GET', '/batch/b1', [], null, BatchData::class];
        yield 'batches status' => [fn(PayfurlClient $c) => $c->batches->status('b1'), 'GET', '/batch/b1/status', [], null, BatchStatus::class];
        yield 'batches search' => [
            fn(PayfurlClient $c) => $c->batches->search(new BatchSearch(description: 'Nov')), 'GET', '/batch', ['description' => 'Nov'], null, BatchList::class,
        ];

        // Payment links
        yield 'paymentLinks create' => [
            fn(PayfurlClient $c) => $c->paymentLinks->create(new CreatePaymentLink(title: 'Donate', amount: 5.0, allowedPaymentTypes: [PaymentType::Card, PaymentType::PayPal])),
            'POST', '/payment_link', [], ['title' => 'Donate', 'amount' => 5.0, 'allowedPaymentTypes' => ['CARD', 'PAYPAL']], PaymentLink::class,
        ];
        yield 'paymentLinks get' => [fn(PayfurlClient $c) => $c->paymentLinks->get('pl1'), 'GET', '/payment_link/pl1', [], null, PaymentLink::class];
        yield 'paymentLinks search' => [
            fn(PayfurlClient $c) => $c->paymentLinks->search(new PaymentLinkSearch(limit: 5)), 'GET', '/payment_link', ['limit' => '5'], null, PaymentLinkList::class,
        ];

        // Tokens
        yield 'tokens get' => [fn(PayfurlClient $c) => $c->tokens->get('t1'), 'GET', '/token/t1', [], null, TokenData::class];
        yield 'tokens search' => [
            fn(PayfurlClient $c) => $c->tokens->search(new TokenSearch(providerId: 'p1')), 'GET', '/token', ['providerId' => 'p1'], null, TokenList::class,
        ];

        // Transfers
        yield 'transfers create without a charge' => [
            fn(PayfurlClient $c) => $c->transfers->create(new NewTransferGroup(
                providerId: 'p1',
                groupReference: 'g1',
                transfers: [new NewTransfer(account: 'acc1', amount: 3.0, currency: 'AUD')],
            )),
            'POST', '/transfer', [], ['providerId' => 'p1', 'groupReference' => 'g1', 'transfers' => [['account' => 'acc1', 'amount' => 3.0, 'currency' => 'AUD']]],
            'list', [['transferId' => 'tr1']],
        ];
        yield 'transfers get' => [fn(PayfurlClient $c) => $c->transfers->get('tr1'), 'GET', '/transfer/tr1', [], null, TransferData::class];
        yield 'transfers search' => [
            fn(PayfurlClient $c) => $c->transfers->search(new TransferSearch(reference: 'g1')), 'GET', '/transfer', ['reference' => 'g1'], null, TransferList::class,
        ];

        // Vaults
        yield 'vaults create' => [
            fn(PayfurlClient $c) => $c->vaults->create(new NewVault(cardNumber: '4111111111111111', expireSeconds: 3600)),
            'POST', '/vault', [], ['cardNumber' => '4111111111111111', 'expireSeconds' => 3600], VaultData::class,
        ];
        yield 'vaults get' => [fn(PayfurlClient $c) => $c->vaults->get('v1'), 'GET', '/vault/v1', [], null, VaultDataWithPCI::class];
        yield 'vaults delete' => [fn(PayfurlClient $c) => $c->vaults->delete('v1'), 'DELETE', '/vault/v1', [], null, VaultData::class];

        // Webhook subscriptions
        yield 'webhookSubscriptions create' => [
            fn(PayfurlClient $c) => $c->webhookSubscriptions->create(new NewWebhookSubscription(url: 'https://shop.example/hook', types: [WebhookType::Transaction, WebhookType::Dispute])),
            'POST', '/webhook/subscription', [], ['url' => 'https://shop.example/hook', 'types' => ['Transaction', 'Dispute']], WebhookSubscription::class,
        ];
        yield 'webhookSubscriptions get' => [fn(PayfurlClient $c) => $c->webhookSubscriptions->get('w1'), 'GET', '/webhook/subscription/w1', [], null, WebhookSubscription::class];
        yield 'webhookSubscriptions search' => [
            fn(PayfurlClient $c) => $c->webhookSubscriptions->search(new WebhookSubscriptionSearch(type: WebhookType::Batch)),
            'GET', '/webhook/subscription', ['type' => 'Batch'], null, WebhookSubscriptionList::class,
        ];
        yield 'webhookSubscriptions delete' => [fn(PayfurlClient $c) => $c->webhookSubscriptions->delete('w1'), 'DELETE', '/webhook/subscription/w1', [], null, WebhookSubscription::class];

        // Settlements, disputes, info, root, version
        yield 'settlements search' => [
            fn(PayfurlClient $c) => $c->settlements->search($date, new \DateTimeImmutable('2026-11-30')),
            'GET', '/settlements', ['startDate' => '2026-11-01', 'endDate' => '2026-11-30'], null, 'list', [['settlementId' => 's1']],
        ];
        yield 'disputes get' => [fn(PayfurlClient $c) => $c->disputes->get('d1'), 'GET', '/dispute/d1', [], null, Dispute::class];
        yield 'disputes search' => [
            fn(PayfurlClient $c) => $c->disputes->search(new DisputeSearch(transactionId: 'ch1', dueBefore: $date)),
            'GET', '/dispute', ['transactionId' => 'ch1', 'dueBefore' => '2026-11-01T10:00:00+00:00'], null, DisputeList::class,
        ];
        yield 'info providers' => [
            fn(PayfurlClient $c) => $c->info->providers(amount: 25.0, currency: 'AUD', cardProviderId: 'p1'),
            'GET', '/info/providers', ['amount' => '25', 'currency' => 'AUD', 'cardProviderId' => 'p1'], null, InfoProviders::class,
        ];
        yield 'root' => [fn(PayfurlClient $c) => $c->root(), 'GET', '/', [], null, RootStatus::class];
        yield 'version' => [fn(PayfurlClient $c) => $c->version(), 'GET', '/version', [], null, ApplicationVersion::class];
    }
}
