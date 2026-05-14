<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Booking;
use App\Models\Table;
use App\Models\Menu;
use Carbon\Carbon;


class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = now()->toDateString();
        $nowTime = now()->format('H:i:s');
        $period = $request->query('period', 'today');
        $period = in_array($period, ['today', 'month', 'year'], true) ? $period : 'today';

        $now = Carbon::now();
        $periodOptions = [
            'today' => 'HARI INI',
            'month' => 'BULAN INI',
            'year' => 'TAHUN INI',
        ];
        $chartSubtitles = [
            'today' => 'Metrik pendapatan per jam',
            'month' => 'Metrik pendapatan per tanggal',
            'year' => 'Metrik pendapatan per bulan',
        ];
        
        // Eager load only active/relevant bookings for today to determine table status.
        $tables = Table::with(['bookings' => function($query) use ($today, $nowTime) {
            $query->where('booking_date', $today)
                ->whereIn('status', ['pending', 'confirmed'])
                ->where('end_time', '>', $nowTime)
                ->orderBy('start_time');
        }])->get();

        $menus = Menu::latest()->get();

        $periodQuery = Booking::query();
        if ($period === 'month') {
            $periodQuery->whereYear('booking_date', $now->year)
                ->whereMonth('booking_date', $now->month);
        } elseif ($period === 'year') {
            $periodQuery->whereYear('booking_date', $now->year);
        } else {
            $periodQuery->whereDate('booking_date', $today);
        }

        $pendapatanHariIni = (clone $periodQuery)
            ->whereIn('status', ['confirmed', 'completed'])
            ->sum('total_price');

        $totalPemesanan = (clone $periodQuery)->count();

        $revenueSeries = [];
        $maxRevenue = 0;

        if ($period === 'month') {
            for ($day = 1; $day <= $now->daysInMonth; $day++) {
                $date = $now->copy()->day($day)->toDateString();
                $revenue = Booking::whereDate('booking_date', $date)
                    ->whereIn('status', ['confirmed', 'completed'])
                    ->sum('total_price');

                $revenueSeries[(string) $day] = [
                    'label' => (string) $day,
                    'revenue' => $revenue,
                ];
                $maxRevenue = max($maxRevenue, $revenue);
            }
        } elseif ($period === 'year') {
            $monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

            for ($month = 1; $month <= 12; $month++) {
                $revenue = Booking::whereYear('booking_date', $now->year)
                    ->whereMonth('booking_date', $month)
                    ->whereIn('status', ['confirmed', 'completed'])
                    ->sum('total_price');

                $revenueSeries[(string) $month] = [
                    'label' => $monthLabels[$month - 1],
                    'revenue' => $revenue,
                ];
                $maxRevenue = max($maxRevenue, $revenue);
            }
        } else {
            for ($hour = 10; $hour <= 22; $hour++) {
                $startHour = sprintf('%02d:00:00', $hour);
                $endHour = sprintf('%02d:59:59', $hour);

                $revenue = Booking::whereDate('booking_date', $today)
                    ->whereIn('status', ['confirmed', 'completed'])
                    ->whereBetween('start_time', [$startHour, $endHour])
                    ->sum('total_price');

                $revenueSeries[(string) $hour] = [
                    'label' => sprintf('%02d:00', $hour),
                    'revenue' => $revenue,
                ];
                $maxRevenue = max($maxRevenue, $revenue);
            }
        }

        $chartData = [];
        foreach ($revenueSeries as $key => $data) {
            $revenue = $data['revenue'];
            $percentage = $maxRevenue > 0 ? ($revenue / $maxRevenue) * 100 : 0;
            $chartData[$key] = [
                'label' => $data['label'],
                'revenue' => $revenue,
                'percentage' => $revenue > 0 ? max(20, $percentage) : 10,
            ];
        }

        $activePeriodLabel = $periodOptions[$period];
        $chartSubtitle = $chartSubtitles[$period];

        return view('dashboard_admin.dashboard', compact('tables', 'menus', 'pendapatanHariIni', 'totalPemesanan', 'chartData', 'period', 'periodOptions', 'activePeriodLabel', 'chartSubtitle'));

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
