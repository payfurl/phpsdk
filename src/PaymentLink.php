<?php

namespace payFURL\Sdk;

require_once(__DIR__ . '/tools/HttpWrapper.php');
require_once(__DIR__ . '/tools/ArrayTools.php');
require_once(__DIR__ . '/tools/UrlTools.php');
require_once(__DIR__ . '/tools/CaseConverter.php');

/**
 * @copyright PayFURL
 */
class PaymentLink
{
    private array $validSearchKeys = ['AddedAfter', 'AddedBefore', 'Limit', 'SortBy', 'Skip'];

    /**
     * @throws ResponseException
     */
    public function Create($params)
    {
        $params = CaseConverter::convertKeysToPascalCase($params);
        ArrayTools::ValidateKeys($params, ['Title']);

        $sourceParams = [
            'Title' => 1,
            'Amount' => 1,
            'Currency' => 1,
            'AllowedPaymentTypes' => 1,
            'Description' => 1,
            'Image' => 1,
            'ConfirmationMessage' => 1,
            'RedirectUrl' => 1,
            'CallToAction' => 1,
            'LimitPayments' => 1,
        ];
        $data = array_intersect_key($params, $sourceParams);

        $data = ArrayTools::CleanEmpty($data);

        return HttpWrapper::CallApi('/payment_link', 'POST', json_encode($data));
    }

    /**
     * @throws ResponseException
     */
    public function Single($tokenId)
    {
        $url = '/payment_link/' . urlencode($tokenId);

        return HttpWrapper::CallApi($url, 'GET', '');
    }

    /**
     * @throws ResponseException
     */
    public function Search($parameters)
    {
        $params = CaseConverter::convertKeysToPascalCase($parameters);
        try {
            $url = '/payment_link' . UrlTools::CreateQueryString($parameters, $this->validSearchKeys);
        } catch (\Exception $ex) {
            throw new ResponseException($ex->getMessage(), 0, 0, false);
        }

        return HttpWrapper::CallApi($url, 'GET', '');
    }

    /**
     * Encodes an image file to a base64 data URI string
     *
     * @param string $filePath Path to the image file
     * @return string Base64-encoded data URI
     * @throws \InvalidArgumentException
     */
    public function EncodeImage(string $filePath): string
    {
        if (empty($filePath)) {
            throw new \InvalidArgumentException("File path cannot be null or empty.");
        }

        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException("File does not exist.");
        }

        $imageBytes = file_get_contents($filePath);
        if ($imageBytes === false || strlen($imageBytes) < 4) {
            throw new \InvalidArgumentException("Invalid file bytes.");
        }

        $contentType = $this->detectImageContentType($imageBytes);

        return "data:{$contentType};base64," . base64_encode($imageBytes);
    }

    /**
     * Detects image content type from file bytes
     *
     * @param string $imageBytes
     * @return string
     * @throws \InvalidArgumentException
     */
    private function detectImageContentType(string $imageBytes): string
    {
        $byte0 = ord($imageBytes[0]);
        $byte1 = ord($imageBytes[1]);
        $byte2 = ord($imageBytes[2]);
        $byte3 = ord($imageBytes[3]);

        // PNG signature: 89 50 4E 47
        if ($byte0 === 0x89 && $byte1 === 0x50 && $byte2 === 0x4E && $byte3 === 0x47) {
            return "image/png";
        }

        // JPEG signature: FF D8 ... FF D9
        if ($byte0 === 0xFF && $byte1 === 0xD8) {
            $lastByte = ord($imageBytes[strlen($imageBytes) - 1]);
            $secondLastByte = ord($imageBytes[strlen($imageBytes) - 2]);
            if ($secondLastByte === 0xFF && $lastByte === 0xD9) {
                return "image/jpeg";
            }
        }

        // GIF signature: 47 49 46 38
        if ($byte0 === 0x47 && $byte1 === 0x49 && $byte2 === 0x46 && $byte3 === 0x38) {
            return "image/gif";
        }

        throw new \InvalidArgumentException("Unsupported image format.");
    }
}
