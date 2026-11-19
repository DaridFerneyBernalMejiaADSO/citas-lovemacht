<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $sample = Profile::query()
            ->where('is_demo', true)
            ->where('first_name', 'Valentina')
            ->whereNotNull('birth_date')
            ->whereDate('birth_date', '<=', today()->subYears(18))
            ->whereHas('user', fn ($query) => $query->where('role', 'user'))
            ->first();

        return view('home', compact('sample'));
    }
}
