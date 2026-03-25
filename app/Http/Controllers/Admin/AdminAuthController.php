<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginUserRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    public function create(): View
    {
        return view('admin.login');
    }

    public function store(LoginUserRequest $request): RedirectResponse
    {
        if (! Auth::attempt($request->only('email', 'password'))) {
            return back()->withErrors(['email' => 'ログイン情報が登録されていません']);
        }

        if (! Auth::user()->is_admin) {
            Auth::logout();
            return back()->withErrors(['email' => 'ログイン情報が登録されていません']);
        }

        $request->session()->regenerate();

        return redirect()->route('admin.attendance.list');
    }
}
