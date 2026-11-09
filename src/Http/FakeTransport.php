<?php

declare(strict_types=1);

namespace payFURL\Sdk\Http;

/**
 * A transport for tests: it records every request and answers with the responses queued on it, in order.
 *
 *     $transport = new FakeTransport();
 *     $transport->queue(201, ['chargeId' => 'c1', 'status' => 'SUCCESS']);
 *     $client = new PayfurlClient('sectest...-au', Environment::Sandbox, new ClientOptions(transport: $transport));
 *     $client->charges()->createWithToken(new NewChargeToken(amount: 20, token: 't1'));
 *     $transport->lastRequest()->url; // https://sandbox-api-au.payfurl.com/charge/token
 */
final class FakeTransport implements Transport
{
    /** @var list<Request> */
    public array $requests = [];

    /** @var list<Response|TransportException> */
    private array $responses = [];

    /**
     * Queues a response. An array body is sent as JSON.
     *
     * @param array<array-key, mixed>|string $body
     */
    public function queue(int $status, array|string $body = ''): self
    {
        $this->responses[] = new Response($status, is_array($body) ? json_encode($body, JSON_THROW_ON_ERROR) : $body);

        return $this;
    }

    /**
     * Queues a network failure (or a timeout) instead of a response.
     */
    public function queueFailure(bool $timedOut = false): self
    {
        $this->responses[] = new TransportException($timedOut ? 'Operation timed out' : 'Could not connect', $timedOut);

        return $this;
    }

    public function send(Request $request): Response
    {
        $this->requests[] = $request;
        $response = array_shift($this->responses)
            ?? throw new \LogicException('FakeTransport: no response queued for ' . $request->method . ' ' . $request->url);
        if ($response instanceof TransportException) {
            throw $response;
        }

        return $response;
    }

    public function lastRequest(): Request
    {
        return $this->requests[array_key_last($this->requests) ?? throw new \LogicException('FakeTransport: no request was sent')];
    }

    /**
     * The last request's JSON body, decoded.
     *
     * @return array<array-key, mixed>
     */
    public function lastBody(): array
    {
        $body = $this->lastRequest()->body;
        $decoded = $body === null ? [] : json_decode($body, true, 512, JSON_THROW_ON_ERROR);

        return is_array($decoded) ? $decoded : [];
    }
}
