<?php
namespace App\Http\Controllers;

use App\Models\Table;

class RateController extends Controller
{
    public function index()
    {
        $tables = Table::all();
        return view('website.tarif', compact('tables'));
    }
}