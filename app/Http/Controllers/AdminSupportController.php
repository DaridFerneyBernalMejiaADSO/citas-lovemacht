<?php

namespace App\Http\Controllers;

use App\Models\SupportRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminSupportController extends Controller
{
    public function index(): View
    {
        $requests = SupportRequest::query()
            ->with(['user.profile'])
            ->orderByRaw("CASE WHEN status = 'abierto' THEN 0 ELSE 1 END")
            ->latest('created_at')
            ->paginate(20);

        return view('admin.support', compact('requests'));
    }

    public function update(Request $request, SupportRequest $supportRequest): RedirectResponse
    {
        $data = $request->validate([
            'admin_response' => ['required', 'string', 'max:2000'],
            'status' => ['required', Rule::in(['respondido', 'cerrado'])],
        ]);
        $user = $request->user();

        abort_unless($user instanceof User, 401);

        $supportRequest->update([
            'admin_response' => trim($data['admin_response']),
            'status' => $data['status'],
            'responded_by' => $user->id_user,
        ]);

        return redirect()->route('admin.support.index')->with('status', 'La respuesta se guardó correctamente.');
    }
}
