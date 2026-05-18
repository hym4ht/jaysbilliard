<?php
namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Support\Facades\Storage;

class PromoController extends Controller
{
    public function index()
    {
        $tableImages = $this->tableImagePaths();

        return view('website.promos', compact('tableImages'));
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
