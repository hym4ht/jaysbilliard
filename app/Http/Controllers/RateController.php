<?php
namespace App\Http\Controllers;

use App\Models\Rate;

class RateController extends Controller
{
    public function index()
    {
        $rates = Rate::orderBy('start_time')->get();
        return view('website.tarif', compact('rates'));
    }
}