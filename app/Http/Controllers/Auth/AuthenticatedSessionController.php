<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
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

        $request->session()->regenerate();

        $user = $request->user();
        $this->clearInvalidIntendedUrl($request, $user);

        $redirectRoute = match (true) {
            $user?->hasRole('admin') => route('filament.admin.pages.dashboard'),
            $user?->hasRole('produksi') => route('filament.produksi.pages.dashboard'),
            $user?->hasRole('keuangan') => route('filament.keuangan.pages.dashboard'),
            default => route('landing', absolute: false),
        };

        $request->session()->flash('toast', [
            'type' => 'success',
            'message' => 'Welcome to BROWSTIME!',
        ]);

        return redirect()->intended($redirectRoute);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $request->session()->flash('toast', [
            'type' => 'success',
            'message' => 'Logout berhasil.',
        ]);

        return redirect('/');
    }

    protected function clearInvalidIntendedUrl(Request $request, ?object $user): void
    {
        if (! $user) {
            return;
        }

        $intended = $request->session()->get('url.intended');
        if (! $intended) {
            return;
        }

        $path = parse_url($intended, PHP_URL_PATH) ?: '';
        $panelPrefixes = [
            'admin' => '/admin',
            'produksi' => '/produksi',
            'keuangan' => '/keuangan',
        ];

        foreach ($panelPrefixes as $role => $prefix) {
            if (Str::startsWith($path, $prefix) && ! $user->hasRole($role)) {
                $request->session()->forget('url.intended');
                break;
            }
        }
    }
}
