<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Table;
use App\Models\Menu;


class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();
        
        // Eager load only confirmed bookings for today to determine table status
        $tables = Table::with(['bookings' => function($query) use ($today) {
            $query->where('booking_date', $today);
        }])->get();

        $menus = Menu::latest()->get();

        // Calculate dynamic stats for today
        $pendapatanHariIni = \App\Models\Booking::where('booking_date', $today)
                                ->whereIn('status', ['confirmed', 'completed'])
                                ->sum('total_price');
                                
        $totalPemesanan = \App\Models\Booking::where('booking_date', $today)->count();

        // Calculate hourly revenue for chart (10:00 to 22:00)
        $hourlyRevenue = [];
        $maxRevenue = 0;
        
        for ($i = 10; $i <= 22; $i++) {
            $startHour = sprintf('%02d:00:00', $i);
            $endHour = sprintf('%02d:59:59', $i);
            
            $revenue = \App\Models\Booking::where('booking_date', $today)
                        ->whereIn('status', ['confirmed', 'completed'])
                        ->whereBetween('start_time', [$startHour, $endHour])
                        ->sum('total_price');
                        
            $hourlyRevenue[$i] = $revenue;
            if ($revenue > $maxRevenue) {
                $maxRevenue = $revenue;
            }
        }
        
        // Calculate percentage for each hour
        $chartData = [];
        foreach ($hourlyRevenue as $hour => $revenue) {
            $percentage = $maxRevenue > 0 ? ($revenue / $maxRevenue) * 100 : 0;
            // Minimum height for visibility if there's revenue, otherwise 10%
            $chartData[$hour] = [
                'revenue' => $revenue,
                'percentage' => $revenue > 0 ? max(20, $percentage) : 10
            ];
        }

        return view('dashboard_admin.dashboard', compact('tables', 'menus', 'pendapatanHariIni', 'totalPemesanan', 'chartData'));

    }

    public function history()
    {
        // Get all bookings to support unified client-side pagination with localStorage
        $bookings = \App\Models\Booking::with(['table', 'user', 'orders'])->latest()->get();
        
        // Basic stats calculations
        $totalBookings = $bookings->count();
        // Since F&B items details are not fully relational yet, we'll just mock it or count orders.
        $totalOrders = $bookings->sum(function ($booking) {
            return $booking->orders->count(); 
        });

        return view('dashboard_admin.pemesanan', compact('bookings', 'totalBookings', 'totalOrders'));
    }

    public function transaksi()
    {
        $transactions = \App\Models\Booking::with(['table', 'user', 'orders'])->latest()->get();
        return view('dashboard_admin.transaksi', compact('transactions'));
    }

    public function confirmBooking($id)
    {
        $booking = \App\Models\Booking::findOrFail($id);
        $booking->status = 'confirmed';
        $booking->save();

        return back()->with('success', 'Booking berhasil dikonfirmasi!');
    }

    public function endSession($id)
    {
        $booking = \App\Models\Booking::findOrFail($id);
        $booking->status = 'completed';
        $booking->save();

        return back()->with('success', 'Sesi permainan telah diakhiri.');
    }

    public function deleteBooking($id)
    {
        $booking = \App\Models\Booking::findOrFail($id);
        $booking->delete();

        return back()->with('success', 'Riwayat pemesanan berhasil dihapus secara permanen.');
    }

    public function completeBooking($id)
    {
        $booking = \App\Models\Booking::findOrFail($id);
        $booking->status = 'completed';
        $booking->save();

        return back()->with('success', 'Pesanan telah diselesaikan.');
    }

    public function profile()
    {
        // Get authenticated user or redirect to login if not authenticated
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        return view('dashboard_admin.profile_settings', compact('user'));
    }
}
