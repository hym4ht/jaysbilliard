<?php
namespace App\Http\Controllers;

use App\Models\Promo;

class HomeController extends Controller
{
    public function index()
    {
        $promos = Promo::where('is_active', true)->latest()->take(2)->get();
        return view('website.home', compact('promos'));
    }

    public function location()
    {
        return view('website.lokasi');
    }
}