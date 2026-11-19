<?php

namespace App\Http\Controllers;

use App\Models\ConnectionMatch;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request, ConnectionMatch $match): RedirectResponse
    {
        $user = $request->user();
        $body = $request->validate([
            'body' => ['required', 'string', 'max:1000'],
        ])['body'];

        abort_unless(
            $user instanceof User
            && in_array((int) $user->id_user, [(int) $match->user_one_id, (int) $match->user_two_id], true),
            404
        );

        Message::create([
            'match_id' => $match->id_match,
            'sender_id' => $user->id_user,
            'body' => trim($body),
        ]);

        return redirect()->route('chats.show', $match);
    }
}
