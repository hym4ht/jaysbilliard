<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\FnbOrder;
use App\Services\MidtransDirectDebitService;
use Illuminate\Support\Facades\Log;
use SnapBi\SnapBi;

class WebhookController extends Controller
{
    public function midtransHandler(Request $request)
    {
        $payload = $request->all();

        if (empty($payload)) {
            $payload = json_decode($request->getContent(), true) ?: [];
        }

        if (empty($payload['order_id']) || empty($payload['transaction_status'])) {
            Log::warning('Midtrans Webhook: payload tidak lengkap', ['payload' => $payload]);

            return response()->json(['message' => 'Invalid webhook payload'], 422);
        }

        if (!$this->signatureIsValid($payload)) {
            Log::warning('Midtrans Webhook: signature tidak valid', [
                'order_id' => $payload['order_id'] ?? null,
            ]);

            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transactionStatus = (string) $payload['transaction_status'];
        $orderId = (string) $payload['order_id'];
        $customField1 = (string) ($payload['custom_field1'] ?? '');
        $fraudStatus = (string) ($payload['fraud_status'] ?? '');

        Log::info('Midtrans Webhook diterima', [
            'order_id' => $orderId,
            'transaction_status' => $transactionStatus,
            'payment_type' => $payload['payment_type'] ?? null,
        ]);

        if (strpos($orderId, 'ORDER-') === 0 && !empty($customField1)) {
            $this->updateBookings($customField1, $transactionStatus, $fraudStatus);
        } elseif (strpos($orderId, 'FNB-') === 0) {
            $this->updateFnbOrder($orderId, $payload, $transactionStatus, $fraudStatus);
        } else {
            Log::warning('Midtrans Webhook: order_id tidak dikenali', ['order_id' => $orderId]);
        }

        return response()->json(['message' => 'Webhook processed successfully'], 200);
    }

    public function snapBiDirectDebitHandler(Request $request, MidtransDirectDebitService $directDebit)
    {
        $payload = $request->all();

        if (empty($payload)) {
            $payload = json_decode($request->getContent(), true) ?: [];
        }

        $orderId = (string) ($payload['originalPartnerReferenceNo'] ?? '');
        if ($orderId === '' || empty($payload['latestTransactionStatus'])) {
            Log::warning('Midtrans Snap-BI Webhook: payload tidak lengkap', ['payload' => $payload]);

            return response()->json(['message' => 'Invalid webhook payload'], 422);
        }

        if (!$this->snapBiSignatureIsValid($request, $payload, $directDebit)) {
            Log::warning('Midtrans Snap-BI Webhook: signature tidak valid', ['order_id' => $orderId]);

            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $paymentStatus = $directDebit->directDebitStatusToPaymentStatus($payload['latestTransactionStatus'] ?? null);

        Log::info('Midtrans Snap-BI Webhook diterima', [
            'order_id' => $orderId,
            'latest_transaction_status' => $payload['latestTransactionStatus'] ?? null,
            'payment_status' => $paymentStatus,
        ]);

        if (strpos($orderId, 'FNB-') === 0) {
            $this->updateFnbOrderFromDirectDebit($orderId, $payload, $paymentStatus);
        } elseif (strpos($orderId, 'ORDER-') === 0) {
            $this->updateBookingsFromDirectDebit($orderId, $paymentStatus);
        } else {
            Log::warning('Midtrans Snap-BI Webhook: order_id tidak dikenali', ['order_id' => $orderId]);
        }

        return response()->json(['message' => 'Webhook processed successfully'], 200);
    }

    private function updateBookings(string $bookingIdsValue, string $transactionStatus, string $fraudStatus): void
    {
        $bookingIds = collect(explode(',', $bookingIdsValue))
            ->map(fn ($id) => trim($id))
            ->filter(fn ($id) => ctype_digit($id))
            ->values()
            ->all();

        if (empty($bookingIds)) {
            Log::warning('Midtrans Webhook: custom_field1 booking kosong', [
                'custom_field1' => $bookingIdsValue,
            ]);

            return;
        }

        $paymentStatus = $this->paymentStatusFromMidtrans($transactionStatus, $fraudStatus);

        if ($paymentStatus === 'paid') {
            Booking::whereIn('id', $bookingIds)->update(['status' => 'confirmed']);
        } elseif (in_array($paymentStatus, ['cancelled', 'failed', 'expired'], true)) {
            Booking::whereIn('id', $bookingIds)->update(['status' => 'cancelled']);
        } elseif ($paymentStatus === 'pending') {
            Booking::whereIn('id', $bookingIds)
                ->where('status', '!=', 'confirmed')
                ->update(['status' => 'pending']);
        }
    }

    private function updateFnbOrder(string $orderId, array $payload, string $transactionStatus, string $fraudStatus): void
    {
        $order = FnbOrder::where('midtrans_order_id', $orderId)->first();

        if (!$order) {
            Log::warning('Midtrans Webhook: F&B order tidak ditemukan', ['order_id' => $orderId]);

            return;
        }

        $paymentStatus = $this->paymentStatusFromMidtrans($transactionStatus, $fraudStatus);

        if ($order->status === 'paid' && $paymentStatus === 'pending') {
            $paymentStatus = 'paid';
        }

        $updates = [
            'status' => $paymentStatus,
            'payment_method' => $payload['payment_type'] ?? $order->payment_method,
            'midtrans_transaction_id' => $payload['transaction_id'] ?? $order->midtrans_transaction_id,
            'midtrans_payload' => $payload,
        ];

        if ($paymentStatus === 'paid' && !$order->paid_at) {
            $updates['paid_at'] = now();
        }

        $order->update($updates);
    }

    private function updateBookingsFromDirectDebit(string $orderId, string $paymentStatus): void
    {
        $bookingIds = $this->bookingIdsFromDirectDebitOrderId($orderId);

        if (empty($bookingIds)) {
            Log::warning('Midtrans Snap-BI Webhook: booking id kosong', ['order_id' => $orderId]);

            return;
        }

        if ($paymentStatus === 'paid') {
            Booking::whereIn('id', $bookingIds)->update(['status' => 'confirmed']);
        } elseif (in_array($paymentStatus, ['cancelled', 'failed', 'expired'], true)) {
            Booking::whereIn('id', $bookingIds)->update(['status' => 'cancelled']);
        } elseif ($paymentStatus === 'pending') {
            Booking::whereIn('id', $bookingIds)
                ->where('status', '!=', 'confirmed')
                ->update(['status' => 'pending']);
        }
    }

    private function updateFnbOrderFromDirectDebit(string $orderId, array $payload, string $paymentStatus): void
    {
        $order = FnbOrder::where('midtrans_order_id', $orderId)->first();

        if (!$order) {
            Log::warning('Midtrans Snap-BI Webhook: F&B order tidak ditemukan', ['order_id' => $orderId]);

            return;
        }

        if ($order->status === 'paid' && $paymentStatus === 'pending') {
            $paymentStatus = 'paid';
        }

        $updates = [
            'status' => $paymentStatus,
            'payment_method' => 'dana',
            'midtrans_transaction_id' => $payload['originalReferenceNo'] ?? $order->midtrans_transaction_id,
            'midtrans_payload' => $payload,
        ];

        if ($paymentStatus === 'paid' && !$order->paid_at) {
            $updates['paid_at'] = now();
        }

        $order->update($updates);
    }

    private function paymentStatusFromMidtrans(string $transactionStatus, string $fraudStatus): string
    {
        if ($transactionStatus === 'capture') {
            return $fraudStatus === 'challenge' ? 'pending' : 'paid';
        }

        if ($transactionStatus === 'settlement') {
            return 'paid';
        }

        if ($transactionStatus === 'pending') {
            return 'pending';
        }

        if ($transactionStatus === 'expire') {
            return 'expired';
        }

        if (in_array($transactionStatus, ['cancel', 'deny'], true)) {
            return 'cancelled';
        }

        if ($transactionStatus === 'failure') {
            return 'failed';
        }

        if (in_array($transactionStatus, ['refund', 'partial_refund'], true)) {
            return 'refunded';
        }

        return 'pending';
    }

    private function signatureIsValid(array $payload): bool
    {
        $requiredFields = ['order_id', 'status_code', 'gross_amount', 'signature_key'];

        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $payload) || $payload[$field] === null || $payload[$field] === '') {
                Log::warning('Midtrans Webhook: signature field kosong', ['field' => $field]);

                return app()->environment('local', 'testing');
            }
        }

        $expectedSignature = hash(
            'sha512',
            $payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . config('services.midtrans.server_key')
        );

        return hash_equals($expectedSignature, (string) $payload['signature_key']);
    }

    private function snapBiSignatureIsValid(Request $request, array $payload, MidtransDirectDebitService $directDebit): bool
    {
        $publicKey = config('services.midtrans.snap_bi.public_key');

        if (blank($publicKey)) {
            return app()->environment('local', 'testing');
        }

        try {
            $directDebit->configure();

            return SnapBi::notification()
                ->withBody($payload)
                ->withSignature((string) $request->header('X-Signature'))
                ->withTimeStamp((string) $request->header('X-Timestamp'))
                ->withNotificationUrlPath('/v1.0/debit/notify')
                ->isWebhookNotificationVerified();
        } catch (\Throwable $e) {
            report($e);

            return false;
        }
    }

    private function bookingIdsFromDirectDebitOrderId(string $orderId): array
    {
        if (!preg_match('/^ORDER-([0-9.]+)-/', $orderId, $matches)) {
            return [];
        }

        return collect(explode('.', $matches[1]))
            ->filter(fn ($id) => ctype_digit($id))
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();
    }
}
