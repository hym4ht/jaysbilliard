<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function midtransHandler(Request $request)
    {
        // Setup Midtrans config
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);

        try {
            $notification = new \Midtrans\Notification();
        } catch (\Exception $e) {
            Log::error('Midtrans Webhook Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error processing webhook'], 500);
        }

        $transactionStatus = $notification->transaction_status;
        $orderId = $notification->order_id;
        $customField1 = $notification->custom_field1 ?? '';

        Log::info("Midtrans Webhook: Order ID {$orderId} status is {$transactionStatus}");

        // Only handle table bookings (which have custom_field1 as booking IDs)
        if (strpos($orderId, 'ORDER-') === 0 && !empty($customField1)) {
            $bookingIds = explode(',', $customField1);
            
            if (in_array($transactionStatus, ['capture', 'settlement'])) {
                Booking::whereIn('id', $bookingIds)->update(['status' => 'confirmed']);
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                Booking::whereIn('id', $bookingIds)->update(['status' => 'cancelled']);
            } elseif ($transactionStatus == 'pending') {
                Booking::whereIn('id', $bookingIds)->update(['status' => 'pending']);
            }
        }
        
        // For FnB (FNB-) we don't have DB tracking currently, so we just return success

        return response()->json(['message' => 'Webhook processed successfully'], 200);
    }
}
