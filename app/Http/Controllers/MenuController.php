<?php
namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::latest()->get();
        return view('dashboard_admin.menu', compact('menus'));
    }

    public function create()
    {
        return view('dashboard_admin.menu.buat_menu');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|in:available,unavailable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menus', 'public');
            $data['image'] = $path;
        }

        Menu::create($data);

        return redirect()->route('admin.menu')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Menu $menu)
    {
        return view('dashboard_admin.menu.edit_menu', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|in:available,unavailable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $data = $request->except(['image', '_token', '_method']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menus', 'public');
            $data['image'] = $path;
        }

        $menu->update($data);

        return redirect()->route('admin.menu')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('admin.menu')->with('success', 'Menu berhasil dihapus.');
    }
}
