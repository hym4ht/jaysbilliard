<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;

class MejaController extends Controller
{
    public function index()
    {
        $tables = Table::all();
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
            'price_per_hour' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
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
            'price_per_hour' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
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
        $table->delete();
        return redirect()->route('admin.meja.index')->with('success', 'Meja berhasil dihapus.');
    }
}
