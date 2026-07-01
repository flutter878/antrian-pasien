<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'nik'      => ['required', 'string', 'digits:16', 'unique:users,nik'],
            'no_hp'    => ['required', 'string', 'max:15', 'regex:/^[0-9+\-\s]+$/'],
            'alamat'   => ['required', 'string', 'max:500'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'nik.required'    => 'NIK wajib diisi.',
            'nik.digits'      => 'NIK harus tepat 16 digit angka.',
            'nik.unique'      => 'NIK sudah terdaftar.',
            'no_hp.required'  => 'Nomor HP wajib diisi.',
            'no_hp.regex'     => 'Format nomor HP tidak valid.',
            'alamat.required' => 'Alamat wajib diisi.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'nik'      => $request->nik,
            'no_hp'    => $request->no_hp,
            'alamat'   => $request->alamat,
            'role'     => 'pasien',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard'));
    }
}
