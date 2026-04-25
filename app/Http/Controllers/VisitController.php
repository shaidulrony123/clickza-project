<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;

class VisitController extends Controller
{
   public function visitDashboard()
    {
        $todayUniqueVisitors = Visit::whereDate('created_at', today())
            ->where('is_unique', true)
            ->count();

        $todayHits = Visit::whereDate('created_at', today())
            ->count();

        $totalUniqueVisitors = Visit::where('is_unique', true)->count();

        $totalHits = Visit::count();

        $countryWise = Visit::selectRaw('country_name, COUNT(*) as total')
            ->where('is_unique', true)
            ->groupBy('country_name')
            ->orderByDesc('total')
            ->get();

        $topPages = Visit::selectRaw('path, COUNT(*) as total')
            ->groupBy('path')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $latestVisits = Visit::latest()
            ->take(20)
            ->get();

        return view('backend.pages.visitor-dashboard', compact(
            'todayUniqueVisitors',
            'todayHits',
            'totalUniqueVisitors',
            'totalHits',
            'countryWise',
            'topPages',
            'latestVisits'
        ));
    }
}
