<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\FnbOrder;
use App\Models\Table;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = \Carbon\Carbon::now()->toDateString();

        // Get total bookings (all time) for the logged-in user
        $totalBookings = Booking::where('customer_name', $user->name)->count();
        
        // Calculate total hours played from DB
        $dbBookings = Booking::where('customer_name', $user->name)->get();
        $totalHours = 0;
        foreach ($dbBookings as $booking) {
            $start = \Carbon\Carbon::parse($booking->start_time);
            $end = \Carbon\Carbon::parse($booking->end_time);
            $totalHours += $end->diffInHours($start);
        }
        
        $tables = Table::with(['bookings' => function($query) use ($today) {
            $query->where('booking_date', $today)
                  ->where('status', 'confirmed');
        }])->get();

        $activeBookingsCount = Booking::where('booking_date', $today)
                                ->where('customer_name', $user->name)
                                ->count();

        // Get recent activities from DB (last 5 bookings)
        $recentActivities = Booking::where('customer_name', $user->name)
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get();

        $topbar_title = "User Dashboard";
        $topbar_sub = "Selamat datang kembali, " . $user->name . ". Pantau pesanan dan poin Anda di sini.";
        
        return view('dashboard_user.dashboard', compact('user', 'totalBookings', 'totalHours', 'tables', 'topbar_title', 'topbar_sub', 'activeBookingsCount', 'recentActivities'));
    }

    public function meja()
    {
        $user = Auth::user();
        $today = \Carbon\Carbon::now()->toDateString();
        $tables = Table::with(['bookings' => function($query) use ($today) {
            $query->where('booking_date', $today);
        }])->get();
        
        $topbar_title = "Meja";
        $topbar_sub = "Pilih meja favorit Anda dan tentukan waktu bermain";

        return view('dashboard_user.meja', compact('user', 'tables', 'topbar_title', 'topbar_sub'));
    }

    public function mejaAvailability(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date_format:Y-m-d',
        ]);

        $bookingsByTable = Booking::where('booking_date', $validated['date'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->get()
            ->sortBy(fn ($booking) => $booking->status === 'confirmed' ? 0 : 1)
            ->groupBy('table_id');

        $statuses = Table::all()->mapWithKeys(function ($table) use ($bookingsByTable) {
            $statusClass = 'available';
            $statusText = 'TERSEDIA';

            if ($table->status === 'maintenance') {
                $statusClass = 'maintenance';
                $statusText = 'MAINTENANCE';
            } else {
                $booking = $bookingsByTable->get($table->id)?->first();

                if ($booking?->status === 'confirmed') {
                    $statusClass = 'occupied';
                    $statusText = 'TERISI';
                } elseif ($booking?->status === 'pending') {
                    $statusClass = 'booked';
                    $statusText = 'DIPESAN';
                }
            }

            return [
                $table->id => [
                    'status' => $statusClass,
                    'text' => $statusText,
                ],
            ];
        });

        return response()->json([
            'statuses' => $statuses,
        ]);
    }

    public function konfirmasi()
    {
        $user = Auth::user();
        $topbar_title = "Konfirmasi Pembayaran Meja";
        $topbar_sub = "Selesaikan pembayaran untuk mengamankan pesanan Anda";

        return view('dashboard_user.konfirmasi_pembayaran', compact('user', 'topbar_title', 'topbar_sub'));
    }

    public function fnb()
    {
        $user = Auth::user();
        $menus = Menu::where('status', 'available')
            ->orderBy('category')
            ->orderBy('name')
            ->get();
        $categories = $menus->pluck('category')
            ->filter()
            ->unique()
            ->values();
        $tables = Table::all();
        
        $topbar_title = "Makanan dan Minuman";
        $topbar_sub = "Pilih menu favorit Anda dan nikmati saat bermain";

        return view('dashboard_user.fnb', compact('user', 'menus', 'categories', 'tables', 'topbar_title', 'topbar_sub'));
    }

    public function fnbKonfirmasi()
    {
        $user = Auth::user();
        $topbar_title = "Konfirmasi Pembayaran Makanan & Minuman";
        $topbar_sub = "Selesaikan pesanan untuk makanan & minuman Anda";

        return view('dashboard_user.fnb_konfirmasi', compact('user', 'topbar_title', 'topbar_sub'));
    }

    public function fnbCheckout(Request $request)
    {
        $validated = $request->validate([
            'table_id' => 'nullable|integer|exists:tables,id',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1|max:99',
        ]);

        // Setup Midtrans
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = (bool) config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $orderId = 'FNB-' . uniqid() . '-' . time();
        $user = Auth::user();
        $menuIds = collect($validated['items'])->pluck('id')->all();
        $menus = Menu::whereIn('id', $menuIds)->get()->keyBy('id');
        $subtotal = 0;
        $itemDetails = [];
        $orderedItems = [];

        foreach ($validated['items'] as $item) {
            $menu = $menus->get($item['id']);
            $quantity = (int) $item['quantity'];
            $price = (int) $menu->price;
            $lineSubtotal = $price * $quantity;
            $subtotal += $lineSubtotal;

            $itemDetails[] = [
                'id' => (string) $menu->id,
                'price' => $price,
                'quantity' => $quantity,
                'name' => substr($menu->name, 0, 50),
            ];

            $orderedItems[] = [
                'menu_id' => $menu->id,
                'name' => $menu->name,
                'category' => $menu->category,
                'price' => $price,
                'quantity' => $quantity,
                'subtotal' => $lineSubtotal,
            ];
        }

        $tax = (int) round($subtotal * 0.1);
        $total = $subtotal + $tax;

        $fnbOrder = FnbOrder::create([
            'user_id' => $user->id,
            'table_id' => $validated['table_id'] ?? null,
            'midtrans_order_id' => $orderId,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'status' => 'pending',
            'items' => $orderedItems,
        ]);

        if ($tax > 0) {
            $itemDetails[] = [
                'id' => 'SERVICE-TAX',
                'price' => $tax,
                'quantity' => 1,
                'name' => 'Service & Tax',
            ];
        }

        $params = array(
            'transaction_details' => array(
                'order_id' => $orderId,
                'gross_amount' => $total,
            ),
            'item_details' => $itemDetails,
            'customer_details' => array(
                'first_name' => $user->name,
                'phone' => $user->phone ?? '-',
            ),
            'custom_field1' => 'fnb',
            'custom_field2' => (string) $fnbOrder->id,
        );

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
        } catch (\Throwable $e) {
            $fnbOrder->update(['status' => 'failed']);
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi Midtrans. Coba lagi beberapa saat.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'snap_token' => $snapToken,
            'order_id' => $orderId,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
        ]);
    }

    public function fnbPaymentStatus(string $orderId)
    {
        $order = FnbOrder::where('midtrans_order_id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'order_id' => $order->midtrans_order_id,
            'status' => $order->status,
            'payment_method' => $order->payment_method,
            'paid_at' => $order->paid_at?->toIso8601String(),
        ]);
    }
}
