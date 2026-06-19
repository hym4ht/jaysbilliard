<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\FnbOrder;
use App\Models\Table;
use App\Models\Menu;
use App\Models\Order;
use App\Models\StockTransaction;
use App\Services\MidtransDirectDebitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        // Auto-confirm bookings whose time has arrived
        Booking::autoConfirmBookings();

        $user = Auth::user();
        $today = \Carbon\Carbon::now()->toDateString();

        // Get total bookings (all time) for the logged-in user
        $totalBookings = $this->userBookingsQuery($user)->count();
        
        // Calculate total hours played from DB
        $dbBookings = $this->userBookingsQuery($user)->get();
        $totalHours = 0;
        foreach ($dbBookings as $booking) {
            $start = \Carbon\Carbon::parse($booking->booking_date . ' ' . $booking->start_time);
            $end = \Carbon\Carbon::parse($booking->booking_date . ' ' . $booking->end_time);
            if ($end->lt($start)) {
                $end->addDay();
            }
            $totalHours += $start->diffInHours($end);
        }
        
        $tables = Table::with(['bookings' => function($query) use ($today) {
            $query->where('booking_date', $today)
                  ->where('status', 'confirmed');
        }])->get();

        $activeBookingsCount = Booking::where('booking_date', $today)
                                ->where(function ($query) use ($user) {
                                    $query->where('user_id', $user->id)
                                        ->orWhere(function ($legacyQuery) use ($user) {
                                            $legacyQuery->whereNull('user_id')
                                                ->where('customer_name', $user->name);
                                        });
                                })
                                ->count();

        // Get recent activities from DB (last 5 bookings/F&B orders)
        $recentActivities = $this->userHistoryItems($user, 5);

        $topbar_title = "User Dashboard";
        $topbar_sub = "Selamat datang kembali, " . $user->name . ". Pantau pesanan dan poin Anda di sini.";
        
        return view('dashboard_user.dashboard', compact('user', 'totalBookings', 'totalHours', 'tables', 'topbar_title', 'topbar_sub', 'activeBookingsCount', 'recentActivities'));
    }

    public function history()
    {
        $user = Auth::user();
        
        $bookings = Booking::with('table')
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere(function ($legacyQuery) use ($user) {
                        $legacyQuery->whereNull('user_id')
                            ->where('customer_name', $user->name);
                    });
            })
            ->orderBy('booking_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();
                            
        $fnbOrders = \App\Models\Order::whereHas('booking', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhere('customer_name', $user->name);
        })
        ->with(['details.menu', 'booking.table'])
        ->orderBy('created_at', 'desc')
        ->get();

        $topbar_title = "Riwayat Pesanan";
        $topbar_sub = "Lihat daftar pesanan dan riwayat bermain Anda";

        return view('dashboard_user.history', compact('user', 'bookings', 'fnbOrders', 'topbar_title', 'topbar_sub'));
    }

    public function meja()
    {
        // Auto-confirm bookings whose time has arrived
        Booking::autoConfirmBookings();

        $user = Auth::user();
        $today = \Carbon\Carbon::now('Asia/Jakarta')->toDateString();
        // Load all tables with all active bookings to support dynamic date selection on frontend
        $tables = Table::with(['bookings' => function($query) {
            $query->whereIn('status', ['confirmed', 'booked', 'pending', 'dipesan', 'paid', 'lunas', 'completed'])
                  ->where('booking_date', '>=', \Carbon\Carbon::now('Asia/Jakarta')->subDays(1))
                  ->orderBy('start_time', 'asc');
        }])->get();
        
        $rates = \App\Models\Rate::orderBy('start_time')->get();
        
        $topbar_title = "Meja";
        $topbar_sub = "Pilih meja favorit Anda dan tentukan waktu bermain";

        return view('dashboard_user.meja', compact('user', 'tables', 'rates', 'topbar_title', 'topbar_sub'));
    }

    public function mejaAvailability(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
        ]);

        $bookingsByTable = Booking::where('booking_date', $validated['date'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->get()
            ->groupBy('table_id');

        $statuses = Table::all()->mapWithKeys(function ($table) use ($bookingsByTable, $validated) {
            $statusClass = 'available';
            $statusText = 'TERSEDIA';

            if ($table->status === 'maintenance') {
                $statusClass = 'maintenance';
                $statusText = 'MAINTENANCE';
            } else {
                $bookings = $bookingsByTable->get($table->id);
                
                if ($bookings) {
                    // If time range is provided, check for time conflicts
                    if (!empty($validated['start_time']) && !empty($validated['end_time'])) {
                        $hasConflict = $bookings->contains(function ($booking) use ($validated) {
                            return $validated['start_time'] < $booking->end_time
                                && $validated['end_time'] > $booking->start_time;
                        });
                        
                        if ($hasConflict) {
                            $conflictingBooking = $bookings
                                ->filter(function ($booking) use ($validated) {
                                    return $validated['start_time'] < $booking->end_time
                                        && $validated['end_time'] > $booking->start_time;
                                })
                                ->sortBy(fn ($booking) => $booking->status === 'confirmed' ? 0 : 1)
                                ->first();
                            
                            if ($conflictingBooking->status === 'confirmed') {
                                $statusClass = 'occupied';
                                $statusText = 'TERISI';
                            } else {
                                $statusClass = 'booked';
                                $statusText = 'DIPESAN';
                            }
                        }
                    } else {
                        // No time range provided, check if table has any booking on this date
                        $booking = $bookings->sortBy(fn ($b) => $b->status === 'confirmed' ? 0 : 1)->first();
                        
                        if ($booking->status === 'confirmed') {
                            $statusClass = 'occupied';
                            $statusText = 'TERISI';
                        } elseif ($booking->status === 'pending') {
                            $statusClass = 'booked';
                            $statusText = 'DIPESAN';
                        }
                    }
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
            ->where('stock', '>', 0)
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

    public function fnbCheckout(Request $request, MidtransDirectDebitService $directDebit)
    {
        $validated = $request->validate([
            'table_id' => 'nullable|integer|exists:tables,id',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1|max:99',
            'payment_method' => 'nullable|string|in:midtrans,qris,dana,gopay',
        ]);

        $paymentMethod = $validated['payment_method'] ?? 'midtrans';
        if ($paymentMethod === 'dana' && !$directDebit->isConfigured()) {
            return response()->json([
                'success' => false,
                'message' => 'DANA redirect belum bisa dipakai karena credential Snap-BI Midtrans belum lengkap: ' . implode(', ', $directDebit->missingConfigKeys()),
            ], 422);
        }

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

        // Validate stock availability
        foreach ($validated['items'] as $item) {
            $menu = $menus->get($item['id']);
            $quantity = (int) $item['quantity'];
            
            if (!$menu->isInStock($quantity)) {
                return response()->json([
                    'success' => false,
                    'message' => "Stok {$menu->name} tidak mencukupi. Stok tersedia: {$menu->stock}",
                ], 422);
            }
        }

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
            'payment_method' => $paymentMethod,
            'items' => $orderedItems,
        ]);

        if ($paymentMethod === 'dana') {
            $directDebitItems = $orderedItems;
            if ($tax > 0) {
                $directDebitItems[] = [
                    'id' => 'SERVICE-TAX',
                    'price' => $tax,
                    'quantity' => 1,
                    'name' => 'Service & Tax',
                    'category' => 'Tax',
                    'subtotal' => $tax,
                ];
            }

            try {
                $response = $directDebit->createDanaPayment(
                    $orderId,
                    $total,
                    $user,
                    $directDebitItems,
                    route('user.fnb.konfirmasi', ['order_id' => $orderId])
                );
            } catch (\Throwable $e) {
                $fnbOrder->update(['status' => 'failed']);
                report($e);

                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage() ?: 'Gagal membuat pembayaran DANA. Coba lagi beberapa saat.',
                ], 500);
            }

            $fnbOrder->update([
                'midtrans_transaction_id' => $response->referenceNo ?? null,
                'midtrans_payload' => $directDebit->responseToArray($response),
            ]);

            return response()->json([
                'success' => true,
                'payment_type' => 'dana_direct',
                'redirect_url' => $directDebit->redirectUrlFromResponse($response),
                'order_id' => $orderId,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
            ]);
        }

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
            'custom_field3' => $paymentMethod,
        );

        $enabledPayments = $this->midtransEnabledPayments($paymentMethod);
        if (!empty($enabledPayments)) {
            $params['enabled_payments'] = $enabledPayments;
        }

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

    public function fnbSuccess(Request $request)
    {
        $orderId = $request->order_id;
        
        // Setup Midtrans
        \Midtrans\Config::$serverKey = trim(config('services.midtrans.server_key'));
        \Midtrans\Config::$isProduction = (bool) config('services.midtrans.is_production');

        try {
            $status = \Midtrans\Transaction::status($orderId);
            $transactionStatus = $status->transaction_status;
            $paymentType = $status->payment_type ?? null;

            if (in_array($transactionStatus, ['settlement', 'capture'])) {
                $order = FnbOrder::where('midtrans_order_id', $orderId)->first();
                
                if ($order && $order->status !== 'paid') {
                    $updates = ['status' => 'paid'];
                    if ($paymentType) {
                        $updates['payment_method'] = $paymentType;
                    }
                    if (!$order->paid_at) {
                        $updates['paid_at'] = now();
                        $this->reduceStockForFnbOrder($order);
                    }
                    $order->update($updates);
                    return response()->json(['success' => true, 'message' => 'Stok berhasil dikurangi']);
                }
            }
            
            return response()->json(['success' => false, 'message' => 'Pembayaran belum lunas atau sudah diproses']);
        } catch (\Exception $e) {
            Log::error('FnB Success Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function fnbPaymentStatus(string $orderId, MidtransDirectDebitService $directDebit)
    {
        $order = FnbOrder::where('midtrans_order_id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($order->payment_method === 'dana' && $order->status === 'pending') {
            try {
                $statusResponse = $directDebit->getDanaPaymentStatus($orderId, $order->midtrans_transaction_id);
                $paymentStatus = $directDebit->paymentStatusFromStatusResponse($statusResponse);
                $updates = [
                    'status' => $paymentStatus,
                    'midtrans_payload' => $directDebit->responseToArray($statusResponse),
                ];

                if ($paymentStatus === 'paid' && !$order->paid_at) {
                    $updates['paid_at'] = now();
                    $this->reduceStockForFnbOrder($order);
                }

                $order->update($updates);
                $order->refresh();
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return response()->json([
            'success' => true,
            'order_id' => $order->midtrans_order_id,
            'status' => $order->status,
            'payment_method' => $order->payment_method,
            'total' => $order->total,
            'paid_at' => $order->paid_at?->toIso8601String(),
        ]);
    }

    private function reduceStockForFnbOrder(FnbOrder $order): void
    {
        $items = $order->items ?? [];
        foreach ($items as $item) {
            $menuId = $item['menu_id'] ?? null;
            $quantity = (int) ($item['quantity'] ?? 0);
            if ($menuId && $quantity > 0) {
                $menu = Menu::find($menuId);
                if ($menu) {
                    $reduced = $menu->reduceStock($quantity);
                    if ($reduced) {
                        StockTransaction::create([
                            'menu_id'  => $menu->id,
                            'type'     => 'out',
                            'quantity' => $quantity,
                            'note'     => 'Penjualan otomatis (FnB Order: ' . $order->midtrans_order_id . ')',
                        ]);
                    }
                }
            }
        }
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Silakan login terlebih dahulu.'], 401);
        }

        // Determine if updating password or personal info
        if ($request->has('current_password') || $request->has('new_password')) {
            $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:6',
            ]);

            if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
                return response()->json(['success' => false, 'message' => 'Kata sandi saat ini salah.'], 422);
            }

            $user->password = \Illuminate\Support\Facades\Hash::make($request->new_password);
            $user->save();

            return response()->json(['success' => true, 'message' => 'Kata sandi berhasil diperbarui.']);
        } else {
            $request->validate([
                'full_name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username,' . $user->id,
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                'phone' => 'required|string|max:20',
            ]);

            $user->name = $request->full_name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save();

            return response()->json(['success' => true, 'message' => 'Profil berhasil diperbarui.']);
        }
    }

    public function checkNotifications()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'notifications' => []], 401);
        }

        // Get 5 latest bookings of this user sorted by updated_at
        $bookings = Booking::with('table')
            ->where('user_id', $user->id)
            ->latest('updated_at')
            ->take(5)
            ->get()
            ->map(function($b) {
                $b->type = 'booking';
                $b->time_ago = $b->updated_at->diffForHumans();
                return $b;
            });

        // Get 5 latest F&B orders of this user
        $fnbOrders = FnbOrder::where('user_id', $user->id)
            ->latest('updated_at')
            ->take(5)
            ->get()
            ->map(function($o) {
                $o->type = 'fnb_order';
                $o->time_ago = $o->updated_at->diffForHumans();
                return $o;
            });

        // Combine and Sort by updated_at desc
        $combined = $bookings->concat($fnbOrders)->sortByDesc('updated_at')->values();

        return response()->json([
            'success' => true,
            'notifications' => $combined
        ]);
    }

    public function profile()
    {
        // Get authenticated user or redirect to login if not authenticated
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        $topbar_title = "Profile Settings";
        $topbar_sub = "Kelola profil, kredensial keamanan, dan preferensi notifikasi Anda";
        
        return view('dashboard_user.profile_settings', compact('user', 'topbar_title', 'topbar_sub'));
    }

    // ============================================================
    // Private Helper Methods
    // ============================================================

    private function userBookingsQuery($user)
    {
        return Booking::with('table')
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere(function ($legacyQuery) use ($user) {
                        $legacyQuery->whereNull('user_id')
                            ->where('customer_name', $user->name);
                    });
            });
    }

    private function userHistoryItems($user, ?int $limit = null)
    {
        $bookingsQuery = $this->userBookingsQuery($user)->latest();
        $fnbOrdersQuery = FnbOrder::with('table')
            ->where('user_id', $user->id)
            ->latest();

        if ($limit) {
            $bookingsQuery->limit($limit);
            $fnbOrdersQuery->limit($limit);
        }

        $bookings = $bookingsQuery->get()->map(function ($booking) {
            $bookingDate = \Carbon\Carbon::parse($booking->booking_date);
            $startAt = \Carbon\Carbon::parse($booking->booking_date . ' ' . $booking->start_time);
            $endAt = \Carbon\Carbon::parse($booking->booking_date . ' ' . $booking->end_time);
            $durationMinutes = max(0, $startAt->diffInMinutes($endAt));
            $status = $this->historyStatusMeta($booking->status);

            return [
                'type' => 'booking',
                'dot_class' => 'dot-cyan',
                'transaction_id' => $booking->order_id ?? 'BKG-' . $booking->id,
                'title' => 'Booking ' . ($booking->table->name ?? 'Meja'),
                'subtitle' => $bookingDate->format('d M Y') . ', ' . $startAt->format('H:i') . ' - ' . $endAt->format('H:i') . ' WIB',
                'description' => 'Durasi ' . $this->durationLabel($durationMinutes),
                'payment_method' => $this->paymentMethodLabel($booking->payment_method),
                'amount' => 'Rp ' . number_format((int) $booking->total_price, 0, ',', '.'),
                'status_label' => $status['label'],
                'status_class' => $status['class'],
                'sort_at' => $booking->created_at,
            ];
        });

        $fnbOrders = $fnbOrdersQuery->get()->map(function ($order) {
            $items = collect($order->items ?? []);
            $itemQuantity = (int) $items->sum(fn ($item) => (int) ($item['quantity'] ?? 0));
            $firstItem = $items->first();
            $firstItemName = is_array($firstItem) ? ($firstItem['name'] ?? 'Pesanan F&B') : 'Pesanan F&B';
            $status = $this->historyStatusMeta($order->status);

            return [
                'type' => 'fnb',
                'dot_class' => 'dot-pink',
                'transaction_id' => $order->order_id ?? 'FNB-' . $order->id,
                'title' => 'F&B - ' . $firstItemName,
                'subtitle' => ($order->created_at ?? now())->format('d M Y, H:i') . ' WIB',
                'description' => ($order->table->name ?? 'Tanpa meja') . ' - ' . max(1, $itemQuantity) . ' item',
                'payment_method' => $this->paymentMethodLabel($order->payment_method),
                'amount' => 'Rp ' . number_format((int) $order->total, 0, ',', '.'),
                'status_label' => $status['label'],
                'status_class' => $status['class'],
                'sort_at' => $order->created_at,
            ];
        });

        $histories = $bookings
            ->concat($fnbOrders)
            ->sortByDesc(fn ($item) => optional($item['sort_at'])->timestamp ?? 0)
            ->values();

        return $limit ? $histories->take($limit)->values() : $histories;
    }

    private function durationLabel(int $minutes): string
    {
        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        if ($hours > 0 && $remainingMinutes > 0) {
            return $hours . ' Jam ' . $remainingMinutes . ' Menit';
        }

        if ($hours > 0) {
            return $hours . ' Jam';
        }

        return max(1, $remainingMinutes) . ' Menit';
    }

    private function historyStatusMeta(?string $status): array
    {
        return match (strtolower((string) $status)) {
            'paid', 'confirmed' => ['label' => 'BERHASIL', 'class' => 'success'],
            'completed' => ['label' => 'SELESAI', 'class' => 'success'],
            'pending' => ['label' => 'MENUNGGU', 'class' => 'pending'],
            'cancelled', 'failed', 'expired' => ['label' => 'GAGAL', 'class' => 'danger'],
            'refunded' => ['label' => 'REFUND', 'class' => 'neutral'],
            default => ['label' => strtoupper((string) ($status ?: 'PENDING')), 'class' => 'neutral'],
        };
    }

    private function paymentMethodLabel(?string $method): string
    {
        return match (strtolower((string) $method)) {
            'qris' => 'QRIS',
            'dana' => 'DANA',
            'gopay' => 'GoPay',
            'midtrans' => 'Midtrans',
            default => $method ? strtoupper($method) : 'Midtrans',
        };
    }

    private function midtransEnabledPayments(?string $paymentMethod): array
    {
        return match ($paymentMethod) {
            'qris', 'gopay' => ['gopay'],
            default => [],
        };
    }
}
