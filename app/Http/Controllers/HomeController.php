<?php
namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        return view('website.home');
    }

    public function location()
    {
        return view('website.lokasi');
    }
}