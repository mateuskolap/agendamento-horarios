<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class PasswordController extends Controller
{
    /**
     * Show the user's password settings page.
     */
    public function edit(): Response
    {
        return Inertia::render('settings/Password');
    }

    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = request()->user();

        if (Hash::check($validated['password'], $user->password)) {
            return back()->with('warning', 'A nova senha não pode ser igual a anterior');
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        if ($user->must_change_password) {
            $user->update(['must_change_password' => false]);
            return redirect()->route('schedulings')->with('success', 'Senha alterada com sucesso!');
        }

        return back()->with('success', 'Senha alterada com sucesso!');
    }
}
