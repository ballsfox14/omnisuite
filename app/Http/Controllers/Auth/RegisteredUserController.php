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
     * Muestra la vista de registro (solo si no hay usuarios).
     */
    public function create(): View
    {
        // Si ya existe al menos un usuario, bloqueamos el registro
        if (User::count() > 0) {
            abort(403, 'El registro está deshabilitado porque ya existe un administrador.');
        }

        return view('auth.register');
    }

    /**
     * Procesa el registro del primer usuario (administrador).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Asignar rol Administrador al primer usuario
        $user->assignRole('Administrador');

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard'));
    }
}