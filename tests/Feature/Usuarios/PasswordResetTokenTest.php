<?php

use App\Models\User;
use Illuminate\Support\Facades\Password;

/**
 * Los tokens del reset por mail viven en la base usuarios (config/auth.php, passwords.users.connection).
 * La única migración que creaba password_reset_tokens en plataforma era legacy y se borró.
 */
test('el_token_de_reset_por_mail_se_guarda_en_la_base_usuarios', function () {
    $usuario = User::factory()->create();

    $token = Password::createToken($usuario);

    expect(Password::tokenExists($usuario, $token))->toBeTrue();
    $this->assertDatabaseHas('password_reset_tokens', ['email' => $usuario->email], 'usuarios');
});
