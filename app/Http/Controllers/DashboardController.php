<?php

namespace App\Http\Controllers;

use App\Models\Booking;
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
        $menus = Menu::all();
        $tables = Table::all();
        
        $topbar_title = "Makanan dan Minuman";
        $topbar_sub = "Pilih menu favorit Anda dan nikmati saat bermain";

        return view('dashboard_user.fnb', compact('user', 'menus', 'tables', 'topbar_title', 'topbar_sub'));
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
            'total_price' => 'required|numeric',
            'items' => 'required|array'
        ]);

        // Setup Midtrans
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $orderId = 'FNB-' . uniqid() . '-' . time();
        $user = Auth::user();

        $params = array(
            'transaction_details' => array(
                'order_id' => $orderId,
                'gross_amount' => $validated['total_price'],
            ),
            'customer_details' => array(
                'first_name' => $user->name,
                'phone' => $user->phone ?? '-',
            ),
        );

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return response()->json([
            'success' => true,
            'snap_token' => $snapToken
        ]);
    }
}
