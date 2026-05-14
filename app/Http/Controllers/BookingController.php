<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Table;
use App\Models\Rate;
use Illuminate\Http\Request;
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
            'booking_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'total_price' => 'required|numeric',
        ]);

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
}
