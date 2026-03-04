<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUpdateUserRequest;
use App\Models\User;
use App\Models\UserStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()->with(['userStatus']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $sortField = $request->get('sort', 'created_at');
        $sortDir = $request->get('dir', 'desc');
        if (in_array($sortField, ['name', 'email', 'created_at'])) {
            $query->orderBy($sortField, $sortDir === 'asc' ? 'asc' : 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $users = $query->paginate(15)->withQueryString();

        return view('dashboard.users', [
            'users' => $users,
            'userStatuses' => UserStatus::all(),
        ]);
    }

    public function updateStatus(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->withErrors(['user' => 'You cannot deactivate your own account.']);
        }

        $request->validate(['user_status_id' => 'required|exists:user_statuses,id']);
        $user->update(['user_status_id' => $request->user_status_id]);

        return back()->with('status', 'User status updated.');
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->withErrors(['user' => 'You cannot change your own admin status.']);
        }

        $request->validate(['is_admin' => 'required|boolean']);
        $user->update(['is_admin' => $request->is_admin]);

        return back()->with('status', 'User admin status updated. Changes apply on next request.');
    }
}
