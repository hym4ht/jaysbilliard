<?php
namespace App\Http\Controllers;

use App\Models\Promo;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::active()->latest()->get();
        return view('website.promos', compact('promos'));
    }
}