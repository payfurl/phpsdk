<?php

declare(strict_types=1);

namespace payFURL\Sdk\Http;

/**
 * The default transport, on PHP's curl extension.
 */
final class CurlTransport implements Transport
{
    public function send(Request $request): Response
    {
        $headers = [];
        foreach ($request->headers as $name => $value) {
            $headers[] = $name . ': ' . $value;
        }

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $request->url,
            CURLOPT_CUSTOMREQUEST => $request->method,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_TIMEOUT_MS => $request->timeoutMilliseconds,
            CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
            CURLOPT_SSL_VERIFYPEER => $request->verifyTls,
            CURLOPT_SSL_VERIFYHOST => $request->verifyTls ? 2 : 0,
        ]);
        if ($request->body !== null) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $request->body);
        }

        $body = curl_exec($curl);
        $errno = curl_errno($curl);
        $status = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);

        if ($errno !== 0 || !is_string($body)) {
            throw new TransportException(
                'Request to ' . $request->url . ' failed: ' . curl_error($curl),
                $errno === CURLE_OPERATION_TIMEDOUT,
            );
        }

        return new Response($status, $body);
    }
}
