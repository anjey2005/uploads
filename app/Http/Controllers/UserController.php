<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * форма смены пароля
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function change_password()
    {
        return view('pages.change_password');
    }


    /**
     * смена пароля
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update_password(Request $request)
    {
        $password = $request->validate([
            'password_old' => ['required', 'current_password', 'exclude'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $request->user()->update($password);

        return redirect()->route('home')->with('status', __('You password updated'));
    }
}
