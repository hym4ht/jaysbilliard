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

        foreach ($validated['table_ids'] as $table_id) {
            $hasConflict = Booking::where('table_id', $table_id)
                ->where('booking_date', $validated['booking_date'])
                ->whereIn('status', ['pending', 'confirmed'])
                ->where('start_time', '<', $validated['end_time'])
                ->where('end_time', '>', $validated['start_time'])
                ->exists();

            if ($hasConflict) {
                throw ValidationException::withMessages([
                    'table_ids' => 'Meja yang dipilih sudah dipesan pada jam tersebut.',
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

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil disimpan!',
                'bookings' => $bookings,
                'snap_token' => $snapToken
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Booking berhasil!');
    }

    public function selectTable()
    {
        return view('dashboard_admin.meja');
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
                    Booking::whereIn('id', $bookingIds)->update(['status' => 'confirmed']);
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
        if ($bookings->every(fn ($booking) => $booking->status === 'confirmed')) {
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
