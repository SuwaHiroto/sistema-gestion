<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
        Fortify::authenticateUsing(function (Request $request) {
            // 1. Buscamos al usuario por su email
            $user = User::where('email', $request->email)->first();

            // 2. Verificamos que el usuario exista y la contraseña sea correcta
            if ($user && Hash::check($request->password, $user->password)) {

                // 3. AQUÍ ESTÁ EL TRUCO: Verificamos si está activo
                // (Asumiendo que tu columna es 'activo' y 1 es activado)
                if ($user->activo == 0) {
                    throw ValidationException::withMessages([
                        Fortify::username() => ['Tu cuenta ha sido dada de baja. Contacta al administrador.'],
                    ]);
                }

                // Si pasa todo, retornamos el usuario para que inicie sesión
                return $user;
            }

            // Si falla usuario o contraseña, retornamos null (login fallido genérico)
            return null;
        });
    }
}
