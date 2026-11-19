<?php

namespace App\Http\Controllers;

use App\Models\ConnectionMatch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MatchController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        abort_unless($user instanceof User, 401);
        $userId = $user->id_user;

        $matches = ConnectionMatch::query()
            ->where('user_one_id', $userId)
            ->orWhere('user_two_id', $userId)
            ->with(['userOne.profile', 'userTwo.profile'])
            ->latest('created_at')
            ->get();

        return view('social.matches', compact('matches'));
    }
}
