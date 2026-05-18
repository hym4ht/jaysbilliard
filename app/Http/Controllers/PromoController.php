<?php
namespace App\Http\Controllers;

class PromoController extends Controller
{
    public function index()
    {
        // Static promos - no database needed
        return view('website.promos');
    }
}