<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException; // Make sure this is present
use App\Models\User; // Make sure this is present

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
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to find the user by email first
        $user = User::where('email', $request->email)->first();

        // If authentication fails (either email doesn't exist or password is wrong)
        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            // Check if a user with that email exists AND if the password was indeed incorrect for that user.
            // Auth::once() attempts authentication for a single request without logging the user in.
            if ($user && !Auth::once($request->only('email', 'password'))) {
                // User found, but password was incorrect
                throw ValidationException::withMessages([
                    'password' => [trans('auth.password_failed')], // Specific "Wrong password" message
                ]);
            } else {
                // User not found, or other generic authentication failure (e.g., email doesn't exist)
                throw ValidationException::withMessages([
                    'email' => [trans('auth.failed')], // Generic "These credentials do not match our records."
                ]);
            }
        }

        $request->session()->regenerate();

        $user = Auth::user();

        // Redirect based on user role
        if ($user->role === 'cashier') {
            return redirect()->route('pos.index');
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
