<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Table;
use App\Models\Rate;
use App\Services\MidtransDirectDebitService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Schema;
use Midtrans\Config;
use Midtrans\Transaction;
use Midtrans\Snap;

class BookingController extends Controller
{
    public function create()
    {
        $tables = collect();
        $rates = collect();

        if (Schema::hasTable('tables')) {
            $tables = Table::where('is_available', true)->get();
        }
        if (Schema::hasTable('rates')) {
            $rates = Rate::all();
        }

        return view('dashboard_admin.pemesanan', compact('tables', 'rates'));
    }

    public function store(Request $request, MidtransDirectDebitService $directDebit)
    {
        $validated = $request->validate([
            'table_ids' => 'required|array',
            'table_ids.*' => 'exists:tables,id',
            'customer_name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'booking_date' => 'required|date_format:Y-m-d',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'total_price' => 'required|numeric',
            'payment_method' => 'nullable|string|in:midtrans,qris,dana,gopay',
        ]);

        $paymentMethod = $validated['payment_method'] ?? 'midtrans';
        if ($paymentMethod === 'dana' && !$directDebit->isConfigured()) {
            throw ValidationException::withMessages([
                'payment_method' => 'DANA redirect belum bisa dipakai karena credential Snap-BI Midtrans belum lengkap: ' . implode(', ', $directDebit->missingConfigKeys()),
            ]);
        }

        [$startTime, $endTime] = $this->validateBookingWindow($validated);
        $validated['start_time'] = $startTime;
        $validated['end_time'] = $endTime;

        // Check for time overlaps for each selected table
        foreach ($validated['table_ids'] as $table_id) {
            $overlap = Booking::where('table_id', $table_id)
                ->where('booking_date', $validated['booking_date'])
                ->whereIn('status', ['pending', 'booked', 'dipesan', 'confirmed', 'paid', 'lunas', 'completed'])
                ->where(function ($query) use ($validated) {
                    $newStart = $validated['start_time'];
                    $newEnd = $validated['end_time'];

                    $query->where(function ($q) use ($newStart, $newEnd) {
                        $q->whereRaw('start_time < end_time')
                          ->where('start_time', '<', $newEnd)
                          ->where('end_time', '>', $newStart);
                    })->orWhere(function ($q) use ($newStart, $newEnd) {
                        $q->whereRaw('start_time > end_time')
                          ->where(function($q2) use ($newStart, $newEnd) {
                              $q2->where('start_time', '<', $newEnd)
                                 ->orWhere('end_time', '>', $newStart);
                          });
                    });
                })
                ->exists();

            if ($overlap) {
                $table = Table::find($table_id);
                $tableName = $table ? $table->name : 'Meja';
                throw ValidationException::withMessages([
                    'table_ids' => ['Meja ' . $tableName . ' sudah dipesan pada tanggal dan jam tersebut. Silakan pilih meja atau waktu lain.']
                ]);
            }
        }

        $bookings = [];
        foreach ($validated['table_ids'] as $table_id) {
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'table_id' => $table_id,
                'customer_name' => $validated['customer_name'],
                'phone' => $validated['phone'] ?? (auth()->user()->phone ?? '-'),
                'booking_date' => $validated['booking_date'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'total_price' => $validated['total_price'] / count($validated['table_ids']), // distribute price
                'status' => 'pending',
                'payment_method' => $paymentMethod,
            ]);
            $bookings[] = $booking;
        }

        // Setup Midtrans
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = (bool) config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $bookingIds = collect($bookings)->pluck('id')->implode(',');
        $orderId = $paymentMethod === 'dana'
            ? 'ORDER-' . str_replace(',', '.', $bookingIds) . '-' . substr(uniqid(), -6)
            : 'ORDER-' . uniqid() . '-' . time();

        if ($paymentMethod === 'dana') {
            try {
                $response = $directDebit->createDanaPayment(
                    $orderId,
                    (int) $validated['total_price'],
                    auth()->user(),
                    [[
                        'id' => 'TABLE',
                        'price' => (int) $validated['total_price'],
                        'quantity' => 1,
                        'name' => 'Reservasi Meja',
                        'category' => 'Billiard',
                    ]],
                    route('user.meja.konfirmasi')
                );
            } catch (\Throwable $e) {
                Booking::whereIn('id', collect($bookings)->pluck('id'))->update(['status' => 'cancelled']);
                report($e);

                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage() ?: 'Gagal membuat pembayaran DANA. Coba lagi beberapa saat.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil disimpan!',
                'bookings' => $bookings,
                'order_id' => $orderId,
                'payment_type' => 'dana_direct',
                'redirect_url' => $directDebit->redirectUrlFromResponse($response),
            ]);
        }

        $params = array(
            'transaction_details' => array(
                'order_id' => $orderId,
                'gross_amount' => $validated['total_price'],
            ),
            'customer_details' => array(
                'first_name' => $validated['customer_name'],
                'phone' => $validated['phone'] ?? (auth()->user()->phone ?? '-'),
            ),
            'custom_field1' => $bookingIds,
            'custom_field2' => $paymentMethod,
        );

        $enabledPayments = $this->midtransEnabledPayments($paymentMethod);
        if (!empty($enabledPayments)) {
            $params['enabled_payments'] = $enabledPayments;
        }

        try {
            $snapToken = Snap::getSnapToken($params);

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil disimpan!',
                'bookings' => $bookings,
                'order_id' => $orderId,
                'snap_token' => $snapToken
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function selectTable()
    {
        $today = \Carbon\Carbon::now('Asia/Jakarta')->toDateString();
        $tables = Table::with(['bookings' => function($query) use ($today) {
            $query->whereIn('status', ['confirmed', 'booked', 'pending', 'dipesan', 'paid', 'lunas', 'completed'])
                  ->where('booking_date', '>=', $today)
                  ->orderBy('booking_date', 'asc')
                  ->orderBy('start_time', 'asc');
        }])->get();
        return view('dashboard_admin.meja', compact('tables'));
    }

    public function bookingSuccess(Request $request)
    {
        $orderId = $request->order_id;
        
        Config::$serverKey = trim(config('services.midtrans.server_key'));
        Config::$isProduction = (bool) config('services.midtrans.is_production');

        try {
            $status = Transaction::status($orderId);
            $transactionStatus = $status->transaction_status;
            $paymentType = $status->payment_type ?? null;

            if (in_array($transactionStatus, ['settlement', 'capture'])) {
                $bookingIds = explode(',', $status->custom_field1);
                $updateData = ['status' => 'booked']; // 'booked' = paid, waiting admin confirmation
                if ($paymentType) {
                    $updateData['payment_method'] = $paymentType;
                }
                Booking::whereIn('id', $bookingIds)->update($updateData);
                return response()->json(['success' => true]);
            }
            return response()->json(['success' => false]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function paymentStatus(string $orderId, MidtransDirectDebitService $directDebit)
    {
        $bookingIds = $this->bookingIdsFromDirectDebitOrderId($orderId);
        if (empty($bookingIds)) {
            abort(404);
        }

        $bookings = Booking::whereIn('id', $bookingIds)
            ->where('user_id', auth()->id())
            ->get();

        if ($bookings->count() !== count($bookingIds)) {
            abort(404);
        }

        if ($bookings->every(fn ($booking) => $booking->status === 'pending')) {
            try {
                $statusResponse = $directDebit->getDanaPaymentStatus($orderId);
                $paymentStatus = $directDebit->paymentStatusFromStatusResponse($statusResponse);

                if ($paymentStatus === 'paid') {
                    Booking::whereIn('id', $bookingIds)->update(['status' => 'booked']); // 'booked' = paid, waiting admin confirmation
                    $bookings = Booking::whereIn('id', $bookingIds)->get();
                } elseif (in_array($paymentStatus, ['cancelled', 'failed', 'expired'], true)) {
                    Booking::whereIn('id', $bookingIds)->update(['status' => 'cancelled']);
                    $bookings = Booking::whereIn('id', $bookingIds)->get();
                }
            } catch (\Throwable $e) {
                report($e);
            }
        }

        $status = 'pending';
        // 'booked' = sudah bayar, menunggu konfirmasi admin. Tampilkan sebagai 'paid' ke user.
        if ($bookings->every(fn ($booking) => in_array($booking->status, ['booked', 'confirmed']))) {
            $status = 'paid';
        } elseif ($bookings->contains(fn ($booking) => $booking->status === 'cancelled')) {
            $status = 'cancelled';
        }

        return response()->json([
            'success' => true,
            'order_id' => $orderId,
            'status' => $status,
            'payment_method' => 'DANA',
        ]);
    }

    // ============================================================
    // Private Helper Methods
    // ============================================================

    private function validateBookingWindow(array $validated): array
    {
        $timezone = config('app.timezone', 'Asia/Jakarta');
        $startTime = $this->normalizeTime($validated['start_time'], 'start_time');
        $endTime = $this->normalizeTime($validated['end_time'], 'end_time');

        $startAt = Carbon::createFromFormat('Y-m-d H:i:s', "{$validated['booking_date']} {$startTime}", $timezone);
        $endAt = Carbon::createFromFormat('Y-m-d H:i:s', "{$validated['booking_date']} {$endTime}", $timezone);

        if ($endAt->lessThanOrEqualTo($startAt)) {
            throw ValidationException::withMessages([
                'end_time' => 'Jam selesai harus lebih besar dari jam mulai.',
            ]);
        }

        if ($startAt->lessThanOrEqualTo(Carbon::now($timezone))) {
            throw ValidationException::withMessages([
                'start_time' => 'Tidak bisa reservasi untuk tanggal atau jam yang sudah lewat.',
            ]);
        }

        return [$startTime, $endTime];
    }

    private function normalizeTime(string $time, string $field): string
    {
        if (!preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $time)) {
            throw ValidationException::withMessages([
                $field => 'Format jam tidak valid.',
            ]);
        }

        $normalized = strlen($time) === 5 ? $time . ':00' : $time;
        [$hour, $minute, $second] = array_map('intval', explode(':', $normalized));

        if ($hour > 23 || $minute > 59 || $second > 59) {
            throw ValidationException::withMessages([
                $field => 'Jam harus berada dalam format 00:00 sampai 23:59.',
            ]);
        }

        return $normalized;
    }

    private function midtransEnabledPayments(?string $paymentMethod): array
    {
        return match ($paymentMethod) {
            'qris', 'gopay' => ['gopay'],
            default => [],
        };
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
