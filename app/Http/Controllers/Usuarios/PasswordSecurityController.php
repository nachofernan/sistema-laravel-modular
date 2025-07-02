<?php

namespace App\Http\Controllers\Usuarios;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;

class PasswordSecurityController extends Controller
{
    //

    public function reset()
    {
        return view('profile.reset-password');
    }

    public function update(Request $request) 
    {
        /** @var \App\Models\Usuarios\User $user */
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'string', 'current_password:web'],
            'password' => ['required', 'string', 'min:8', 'different:current_password', 'regex:^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]^'],
            'password_confirmation' => ['required', 'same:password'],
        ], [
            'required' => __('El campo está vacío'),
            'min' => __('La contraseña debe tener al menos 8 caracteres'),
            'current_password.current_password' => __('La contraseña actual ingresada es incorrecta.'),
            'password_confirmation.same' => __('La nueva contraseña y su confirmación no coinciden.'),
            'password.different' => __('La nueva contraseña debe ser distinta a la anterior.'),
            'password.regex' => __('La nueva contraseña debe tener al menos una mayúscula, una minúscula y un número.'),
        ]);

        $user->update([
            'password' => bcrypt($request->all()['password']),
        ]);

        $user->passwordSecurity->update([
            'password_updated_at' => \Carbon\Carbon::now(),
        ]);

        return redirect()->route('home.dashboard')->with('mensaje_verde', '¡La contraseña fue actualizada con éxito!');
        
    }
}
