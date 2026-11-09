<?php

declare(strict_types=1);

namespace payFURL\Sdk\Resource;

use payFURL\Sdk\Exception\InvalidRequestException;
use payFURL\Sdk\Internal\ApiClient;
use payFURL\Sdk\Model\CreatePaymentLink;
use payFURL\Sdk\Model\PaymentLink;
use payFURL\Sdk\Model\PaymentLinkList;
use payFURL\Sdk\Model\PaymentLinkSearch;

/**
 * Hosted payment pages (PaymentLinkController).
 */
final class PaymentLinks
{
    private const IMAGE_TYPES = ['image/png', 'image/jpeg', 'image/gif'];

    /** @internal */
    public function __construct(private readonly ApiClient $api) {}

    /** `POST /payment_link` */
    public function create(CreatePaymentLink $paymentLink): PaymentLink
    {
        return PaymentLink::fromArray($this->api->post('/payment_link', $paymentLink->toArray()));
    }

    /** `GET /payment_link/{paymentLinkId}` */
    public function get(string $paymentLinkId): PaymentLink
    {
        return PaymentLink::fromArray($this->api->get('/payment_link/' . ApiClient::id($paymentLinkId, 'paymentLinkId')));
    }

    /** `GET /payment_link` */
    public function search(PaymentLinkSearch $search = new PaymentLinkSearch()): PaymentLinkList
    {
        return PaymentLinkList::fromArray($this->api->get('/payment_link', $search->toQuery()));
    }

    /**
     * A PNG, JPEG or GIF file as the data URI that `CreatePaymentLink::$image` takes.
     *
     * @throws InvalidRequestException when the file is missing or not one of those images
     */
    public static function imageDataUri(string $path): string
    {
        $bytes = is_file($path) ? file_get_contents($path) : false;
        if ($bytes === false) {
            throw new InvalidRequestException('No image file at ' . $path);
        }
        $type = getimagesizefromstring($bytes)['mime'] ?? null;
        if (!in_array($type, self::IMAGE_TYPES, true)) {
            throw new InvalidRequestException($path . ' is not a PNG, JPEG or GIF image');
        }

        return 'data:' . $type . ';base64,' . base64_encode($bytes);
    }
}
