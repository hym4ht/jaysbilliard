<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use SnapBi\SnapBiConfig;

class MidtransDirectDebitService
{
    public function isConfigured(): bool
    {
        return empty($this->missingConfigKeys());
    }

    public function missingConfigKeys(): array
    {
        $config = config('services.midtrans.snap_bi', []);
        $required = [
            'client_id',
            'private_key',
            'client_secret',
            'partner_id',
            'channel_id',
            'merchant_id',
        ];

        return array_values(array_filter($required, fn ($key) => blank(Arr::get($config, $key))));
    }

    public function createDanaPayment(
        string $orderId,
        int $amount,
        object $user,
        array $items,
        string $returnUrl
    ): object {
        $this->configure();

        $accessToken = $this->getAccessToken();
        $response = $this->createDirectDebitPayment(
            $orderId,
            $this->buildDanaPayload($orderId, $amount, $user, $items, $returnUrl, $accessToken),
            $accessToken
        );

        if (!isset($response->webRedirectUrl) && !isset($response->appRedirectUrl)) {
            $this->throwMidtransError($response, 'pembayaran DANA');
        }

        return $response;
    }

    public function getDanaPaymentStatus(string $orderId, ?string $referenceNo = null): object
    {
        $this->configure();

        $accessToken = $this->getAccessToken();
        $body = [
            'serviceCode' => '54',
        ];

        if ($referenceNo) {
            $body['originalReferenceNo'] = $referenceNo;
        } else {
            $body['originalExternalId'] = $orderId;
            $body['originalPartnerReferenceNo'] = $orderId;
        }

        return $this->requestDirectDebitStatus($orderId, $body, $accessToken);
    }

    public function redirectUrlFromResponse(object $response): ?string
    {
        return $response->webRedirectUrl ?? $response->appRedirectUrl ?? null;
    }

    public function responseToArray(object $response): array
    {
        return json_decode(json_encode($response), true) ?: [];
    }

    public function directDebitStatusToPaymentStatus(?string $status): string
    {
        return match ((string) $status) {
            '00' => 'paid',
            '03' => 'pending',
            '04' => 'refunded',
            '05' => 'cancelled',
            '06', '09' => 'failed',
            '08' => 'expired',
            default => 'pending',
        };
    }

    public function paymentStatusFromStatusResponse(object $response): string
    {
        return $this->directDebitStatusToPaymentStatus(
            $response->latestTransactionStatus ?? $response->transactionStatus ?? null
        );
    }

    public function configure(): void
    {
        $missing = $this->missingConfigKeys();
        if (!empty($missing)) {
            throw new RuntimeException('Credential Snap-BI Midtrans belum lengkap: ' . implode(', ', $missing));
        }

        $config = config('services.midtrans.snap_bi');

        SnapBiConfig::$isProduction = (bool) config('services.midtrans.is_production');
        SnapBiConfig::$snapBiClientId = $config['client_id'];
        SnapBiConfig::$snapBiPrivateKey = $this->normalizePrivateKey($config['private_key']);
        SnapBiConfig::$snapBiClientSecret = $config['client_secret'];
        SnapBiConfig::$snapBiPartnerId = $config['partner_id'];
        SnapBiConfig::$snapBiChannelId = $config['channel_id'];
        SnapBiConfig::$snapBiPublicKey = $this->normalizePrivateKey($config['public_key'] ?? '');
    }

    private function buildDanaPayload(
        string $orderId,
        int $amount,
        object $user,
        array $items,
        string $returnUrl,
        string $chargeToken
    ): array {
        $amountValue = number_format($amount, 2, '.', '');
        $phone = $user->phone ?? '-';
        $name = $user->name ?? 'Customer';

        return [
            'partnerReferenceNo' => $orderId,
            'chargeToken' => $chargeToken,
            'merchantId' => config('services.midtrans.snap_bi.merchant_id'),
            'urlParam' => [
                [
                    'url' => $returnUrl,
                    'type' => 'PAY_RETURN',
                    'isDeeplink' => 'Y',
                ],
            ],
            'validUpTo' => Carbon::now(config('app.timezone', 'Asia/Jakarta'))->addMinutes(15)->format('c'),
            'payOptionDetails' => [
                [
                    'payMethod' => 'DANA',
                    'payOption' => 'DANA',
                    'transAmount' => [
                        'value' => $amountValue,
                        'currency' => 'IDR',
                    ],
                ],
            ],
            'additionalInfo' => [
                'customerDetails' => [
                    'phone' => $phone,
                    'firstName' => $name,
                ],
                'items' => $this->formatItems($items),
                'metadata' => [
                    'source' => 'jays-billiard',
                ],
            ],
        ];
    }

    private function getAccessToken(): string
    {
        $timestamp = Carbon::now(config('app.timezone', 'Asia/Jakarta'))->format('c');
        $body = ['grantType' => 'client_credentials'];
        $response = $this->postJson(
            '/v1.0/access-token/b2b',
            [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-CLIENT-KEY' => SnapBiConfig::$snapBiClientId,
                'X-TIMESTAMP' => $timestamp,
                'X-SIGNATURE' => $this->asymmetricSignature($timestamp),
            ],
            $body
        );

        if (empty($response->accessToken)) {
            $this->throwMidtransError($response, 'access token Snap-BI');
        }

        return (string) $response->accessToken;
    }

    private function createDirectDebitPayment(string $orderId, array $body, string $accessToken): object
    {
        $path = '/v1.0/debit/payment-host-to-host';
        $timestamp = Carbon::now(config('app.timezone', 'Asia/Jakarta'))->format('c');

        return $this->postJson(
            $path,
            [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $accessToken,
                'X-PARTNER-ID' => SnapBiConfig::$snapBiPartnerId,
                'X-EXTERNAL-ID' => $orderId,
                'CHANNEL-ID' => SnapBiConfig::$snapBiChannelId,
                'X-TIMESTAMP' => $timestamp,
                'X-SIGNATURE' => $this->symmetricSignature($accessToken, $body, 'POST', $path, $timestamp),
            ],
            $body
        );
    }

    private function requestDirectDebitStatus(string $orderId, array $body, string $accessToken): object
    {
        $path = '/v1.0/debit/status';
        $timestamp = Carbon::now(config('app.timezone', 'Asia/Jakarta'))->format('c');

        return $this->postJson(
            $path,
            [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $accessToken,
                'X-PARTNER-ID' => SnapBiConfig::$snapBiPartnerId,
                'X-EXTERNAL-ID' => 'ST' . now(config('app.timezone', 'Asia/Jakarta'))->format('YmdHis') . random_int(1000, 9999),
                'CHANNEL-ID' => SnapBiConfig::$snapBiChannelId,
                'X-TIMESTAMP' => $timestamp,
                'X-SIGNATURE' => $this->symmetricSignature($accessToken, $body, 'POST', $path, $timestamp),
            ],
            $body
        );
    }

    private function postJson(string $path, array $headers, array $body): object
    {
        $json = json_encode($body, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $response = Http::withHeaders($headers)
            ->withBody($json, 'application/json')
            ->post($this->baseUrl() . $path);

        return json_decode($response->body()) ?: (object) [
            'responseCode' => (string) $response->status(),
            'responseMessage' => $response->body() ?: 'Empty response from Midtrans.',
        ];
    }

    private function asymmetricSignature(string $timestamp): string
    {
        $signature = null;
        $ok = openssl_sign(
            SnapBiConfig::$snapBiClientId . '|' . $timestamp,
            $signature,
            SnapBiConfig::$snapBiPrivateKey,
            OPENSSL_ALGO_SHA256
        );

        if (!$ok) {
            throw new RuntimeException('Private key Snap-BI tidak bisa dipakai untuk membuat signature.');
        }

        return base64_encode($signature);
    }

    private function symmetricSignature(
        string $accessToken,
        array $body,
        string $method,
        string $path,
        string $timestamp
    ): string {
        $minifiedBody = json_encode($body, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $hashedBody = strtolower(bin2hex(hash('sha256', $minifiedBody, true)));
        $payload = strtoupper($method) . ':' . $path . ':' . $accessToken . ':' . $hashedBody . ':' . $timestamp;

        return base64_encode(hash_hmac('sha512', $payload, SnapBiConfig::$snapBiClientSecret, true));
    }

    private function throwMidtransError(object $response, string $stage): never
    {
        $code = $response->responseCode ?? null;
        $message = $response->responseMessage ?? 'Midtrans tidak mengembalikan response yang valid.';
        $prefix = $code ? "[{$code}] " : '';

        throw new RuntimeException("Gagal membuat {$stage}: {$prefix}{$message}");
    }

    private function baseUrl(): string
    {
        return SnapBiConfig::$isProduction
            ? SnapBiConfig::SNAP_BI_PRODUCTION_BASE_URL
            : SnapBiConfig::SNAP_BI_SANDBOX_BASE_URL;
    }

    private function formatItems(array $items): array
    {
        return array_map(function (array $item, int $index) {
            $price = (int) ($item['price'] ?? $item['subtotal'] ?? 0);

            return [
                'id' => (string) ($item['id'] ?? $item['menu_id'] ?? $index + 1),
                'price' => [
                    'value' => number_format($price, 2, '.', ''),
                    'currency' => 'IDR',
                ],
                'quantity' => (int) ($item['quantity'] ?? 1),
                'name' => substr((string) ($item['name'] ?? 'Item'), 0, 50),
                'category' => substr((string) ($item['category'] ?? 'Order'), 0, 50),
                'merchantName' => "Jay's Billiard",
            ];
        }, array_values($items), array_keys(array_values($items)));
    }

    private function normalizePrivateKey(?string $key): string
    {
        return str_replace('\\n', "\n", trim((string) $key));
    }
}
