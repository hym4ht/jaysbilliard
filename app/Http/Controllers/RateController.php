<?php
namespace App\Http\Controllers;

use App\Models\Rate;
use App\Models\Table;
use Illuminate\Support\Facades\Storage;

class RateController extends Controller
{
    public function index()
    {
        $rates = Rate::orderBy('start_time')->get();
        $tableImages = $this->tableImagePaths();

        return view('website.tarif', compact('rates', 'tableImages'));
    }

    private function tableImagePaths()
    {
        $images = Table::query()
            ->whereNotNull('image')
            ->where('image', '<>', '')
            ->pluck('image')
            ->filter(fn ($path) => Storage::disk('public')->exists($path))
            ->values();

        if ($images->isNotEmpty()) {
            return $images;
        }

        return collect(Storage::disk('public')->files('tables'))
            ->filter(fn ($path) => Storage::disk('public')->exists($path))
            ->values();
    }
}
