<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show()
    {
        // Admin is not allowed to access this profile page, they must change DB directly.
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('dashboard')->with('error', 'Admin tidak memiliki akses ke pengaturan profil.');
        }

        return view('profile');
    }

    public function updatePassword(Request $request)
    {
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('dashboard')->with('error', 'Admin tidak diizinkan mengubah password.');
        }

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ]);

        $user = auth()->user();
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password berhasil diubah.');
    }
}
