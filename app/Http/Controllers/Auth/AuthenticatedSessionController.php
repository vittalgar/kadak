<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // Use the global request() helper for session management
        request()->session()->regenerate();

        $user = Auth::user();

        // Check the user's role and redirect accordingly
        if (strtolower($user->role->name) === 'admin') {
            return redirect()->intended(route('dashboard'));
        }

        if (strtolower($user->role->name) === 'agent') {
            return redirect()->intended(route('agent.dashboard'));
        }

        // As a fallback, if the user has no role, log them out.
        Auth::guard('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/login')->withErrors(['email' => 'Your account does not have a valid role assigned.']);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        // Use the global request() helper for session management
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }
}
