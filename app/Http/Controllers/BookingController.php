<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Table;
use App\Models\Rate;
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

    public function store(Request $request)
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
        ]);

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
            ]);
            $bookings[] = $booking;
        }

        // Setup Midtrans
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = (bool) config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $orderId = 'ORDER-' . uniqid() . '-' . time();
        
        $bookingIds = collect($bookings)->pluck('id')->implode(',');

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
        );

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
}
