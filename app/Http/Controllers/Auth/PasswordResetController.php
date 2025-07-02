<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\Auth\CustomResetPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    //
    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }
    
    public function sendResetLink(Request $request)
    {
        $user = User::where('legajo', $request->legajo)->first();
        
        if (!$user || !$user->email || !str_ends_with($user->email, '@buenosairesenergia.com.ar')) {
            return back()->withErrors(['legajo' => 'Usuario no encontrado o sin email registrado o correo no corresponde a Buenos Aires Energía']);
        }

        // Genera y guarda el token
        $token = Password::createToken($user);
        
        // Envía el email con el link
        Mail::to($user->email)->send(new CustomResetPassword($token, $user));

        return back()->with('status', 'Link de recuperación enviado!');
    }

    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'string', 'min:8', 'different:current_password', 'regex:^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]^'],
            'password_confirmation' => ['required', 'same:password'],
        ], [
            'required' => __('El campo está vacío'),
            'min' => __('La contraseña debe tener al menos 8 caracteres'),
            'password_confirmation.same' => __('La nueva contraseña y su confirmación no coinciden.'),
            'password.different' => __('La nueva contraseña debe ser distinta a la anterior.'),
            'password.regex' => __('La nueva contraseña debe tener al menos una mayúscula, una minúscula y un número.'),
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password),
                    'remember_token' => Str::random(60),
                ])->save();
        
                $user->passwordSecurity->update([
                    'password_updated_at' => \Carbon\Carbon::now(),
                ]);

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
}
