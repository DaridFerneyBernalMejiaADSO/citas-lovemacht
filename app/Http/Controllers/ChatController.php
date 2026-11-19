<?php

namespace App\Http\Controllers;

use App\Models\ConnectionMatch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        abort_unless($user instanceof User, 401);
        $userId = $user->id_user;

        $matches = ConnectionMatch::query()
            ->where('user_one_id', $userId)
            ->orWhere('user_two_id', $userId)
            ->with([
                'userOne.profile',
                'userTwo.profile',
                'latestMessage',
            ])
            ->latest('created_at')
            ->get();

        return view('social.chats.index', compact('matches'));
    }

    public function show(Request $request, ConnectionMatch $match): View
    {
        $user = $request->user();
        abort_unless($user instanceof User, 401);
        $userId = (int) $user->id_user;

        abort_unless(
            in_array($userId, [(int) $match->user_one_id, (int) $match->user_two_id], true),
            404
        );

        $match->load(['userOne.profile', 'userTwo.profile']);
        $other = $match->otherUser($userId);

        abort_unless($other && $other->profile, 404);

        $messages = $match->messages()->orderBy('created_at')->get();

        return view('social.chats.show', compact('match', 'other', 'messages'));
    }
}
