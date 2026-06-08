<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;

class MejaController extends Controller
{
    public function index()
    {
        $today = \Carbon\Carbon::now('Asia/Jakarta')->toDateString();
        $tables = Table::with(['bookings' => function($query) use ($today) {
            $query->whereIn('status', ['confirmed', 'booked', 'pending', 'dipesan'])
                  ->where('booking_date', '>=', $today)
                  ->orderBy('booking_date', 'asc')
                  ->orderBy('start_time', 'asc');
        }])->get();
        return view('dashboard_admin.meja', compact('tables'));
    }

    public function create()
    {
        return view('dashboard_admin.meja.buat_meja');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:regular,vip',
            'price_per_hour' => 'required|numeric',
            'capacity' => 'required|integer',
            'description' => 'nullable|string',
            'status' => 'required|in:active,maintenance',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('tables', 'public');
            $validated['image'] = $path;
        }

        // Ensure is_available is set to true for new tables
        $validated['is_available'] = true;

        Table::create($validated);

        return redirect()->route('admin.meja.index')->with('success', 'Meja berhasil ditambahkan.');
    }

    public function edit(Table $table)
    {
        return view('dashboard_admin.meja.edit_meja', compact('table'));
    }

    public function update(Request $request, Table $table)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:regular,vip',
            'price_per_hour' => 'required|numeric',
            'capacity' => 'required|integer',
            'description' => 'nullable|string',
            'status' => 'required|in:active,maintenance',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $data = $request->except(['image', '_token', '_method']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('tables', 'public');
            $data['image'] = $path;
        }

        $table->update($data);

        return redirect()->route('admin.meja.index')->with('success', 'Data meja berhasil diperbarui.');
    }

    public function destroy(Table $table)
    {
        \Illuminate\Support\Facades\DB::transaction(function() use ($table) {
            // Delete related bookings and their children
            foreach ($table->bookings as $booking) {
                foreach ($booking->orders as $order) {
                    // Delete order details
                    $order->details()->delete();
                    // Delete the order
                    $order->delete();
                }
                // Delete the booking
                $booking->delete();
            }

            // Delete image from storage if exists
            if ($table->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($table->image);
            }

            // Finally delete the table
            $table->delete();
        });

        return redirect()->route('admin.meja.index')->with('success', 'Meja dan seluruh data terkait berhasil dihapus.');
    }
}
