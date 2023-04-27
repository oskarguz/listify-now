<?php

namespace App\Http\Controllers;


use App\Enum\SocialAuthenticationDriver;
use App\Models\User;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends Controller
{

    public function login()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        return view('security.login');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function authRedirect(Request $request)
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        $driverStr = (string) $request->query->get('driver');

        $driver = SocialAuthenticationDriver::tryFrom($driverStr);
        if (!$driver) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return Socialite::driver($driver->getSlug())->redirect();
    }

    public function authCallback(Request $request)
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        $driverStr = (string) $request->query->get('driver');

        $driver = SocialAuthenticationDriver::tryFrom($driverStr);
        if (!$driver) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $extUser = Socialite::driver($driver->getSlug())->user();

        $user = User::updateOrCreate([
            'username' => $extUser->getNickname() ?: $extUser->getId()
        ], [
            'name' => (string) $extUser->getName(),
            'email' => $extUser->getEmail(),
            'avatar' => $extUser->getAvatar(),
            'external_id' => $extUser->getId(),
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }
}
