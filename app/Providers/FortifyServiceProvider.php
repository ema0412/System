<?php

namespace App\Providers;

use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Fortify::registerView(fn () => view('auth.register'));
        Fortify::loginView(fn () => view('auth.login'));
        Fortify::verifyEmailView(fn () => view('auth.verify-email'));

        Fortify::createUsersUsing(function (RegisterUserRequest $request): User {
            return User::query()->create([
                'name' => $request->string('name'),
                'email' => $request->string('email'),
                'password' => Hash::make($request->string('password')),
            ]);
        });

        Fortify::authenticateUsing(function (LoginUserRequest $request): ?User {
            $user = User::query()->where('email', $request->string('email'))->first();
            if ($user === null || ! Hash::check($request->string('password'), $user->password)) {
                return null;
            }

            return $user;
        });

        Fortify::redirects('register', '/attendance');
        Fortify::redirects('login', '/attendance');
        Fortify::redirects('email-verification', '/attendance');
    }
}
