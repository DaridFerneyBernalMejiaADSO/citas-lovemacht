<?php

namespace App\Http\Controllers;

use App\Models\ConnectionMatch;
use App\Models\Profile;
use App\Models\Swipe;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DiscoverController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(401);
        }

        $profiles = Profile::query()
            ->with('user')
            ->where('user_id', '<>', $user->id_user)
            ->whereNotNull('birth_date')
            ->whereDate('birth_date', '<=', today()->subYears(18))
            ->whereHas('user', fn ($query) => $query->where('role', 'user'))
            ->whereNotIn('user_id', $user->swipesSent()->select('target_id'))
            ->inRandomOrder()
            ->limit(30)
            ->get();

        return view('social.discover', compact('profiles'));
    }

    public function action(Request $request, User $target): RedirectResponse
    {
        $data = $request->validate([
            'action' => ['required', Rule::in(['like', 'pass'])],
        ]);
        $user = $request->user();

        abort_unless(
            $user instanceof User
            && (int) $user->id_user !== (int) $target->id_user
            && $target->role === 'user'
            && $target->profile
            && $target->profile->birth_date
            && $target->profile->age >= 18,
            404
        );

        $matched = DB::transaction(function () use ($data, $target, $user): bool {
            Swipe::updateOrCreate(
                ['swiper_id' => $user->id_user, 'target_id' => $target->id_user],
                ['action' => $data['action']]
            );

            if ($data['action'] !== 'like') {
                return false;
            }

            $isMutual = Swipe::query()
                ->where('swiper_id', $target->id_user)
                ->where('target_id', $user->id_user)
                ->where('action', 'like')
                ->exists();

            if (! $isMutual) {
                return false;
            }

            $ids = [$user->id_user, $target->id_user];
            sort($ids, SORT_NUMERIC);

            ConnectionMatch::firstOrCreate([
                'user_one_id' => $ids[0],
                'user_two_id' => $ids[1],
            ]);

            return true;
        });

        return redirect()
            ->route('discover.index')
            ->with('status', $matched ? '¡Hay una nueva coincidencia! Ya pueden conversar.' : 'Tu decisión se guardó.');
    }
}
