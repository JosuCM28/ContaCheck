<?php

use App\Models\User;
use App\Models\Client;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $token = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'token' => ['required', 'string', 'max:255'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $client = Client::where('token', $validated['token'])->first();
        if (!$client || $client->user_id) {
            $this->addError('token', __('Invalid token or taken.'));
            return;
        }

        // Crear usuario con valores validados (rol = 'cliente' por default en DB)
        $user = User::create($validated);
        $user->refresh(); // Forzar que Laravel recargue el valor por defecto de 'rol' desde la BD

        // Asociar cliente al nuevo usuario
        $client->user_id = $user->id;
        $client->save();

        // Disparar evento y autenticar
        event(new Registered($user));
        Auth::login($user);

        // Redirigir según rol
        $redirectTo = match ($user->rol) {
            'cliente' => route('client.dashboard', absolute: false),
            'contador' => route('dashboard', absolute: false),
            default => '/',
        };

        $this->redirectIntended(default: $redirectTo, navigate: true);
    }

}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Crear cuenta')" :description="__('ingresa tus datos acontinuación para crear tu cuenta')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-6">
        <!-- Name -->
        <flux:input
            wire:model="name"
            :label="__('Nombre')"
            type="text"
            required
            autofocus
            autocomplete="name"
            :placeholder="__('Nombre completo')"
        />

        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Correo')"
            type="email"
            required
            autocomplete="email"
            placeholder="correo@ejemplo.com"
        />

        <!-- Password -->
        <flux:input
            wire:model="password"
            :label="__('Contraseña')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Contraseña')"
            viewable
        />

        <!-- Confirm Password -->
        <flux:input
            wire:model="password_confirmation"
            :label="__('Confirma contraseña')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Confirmar contraseña')"
            viewable
        />

        <!-- Token -->
        <flux:input
            wire:model="token"
            :label="__('Token')"
            type="text"
            required
            autofocus
            autocomplete="token"
            :placeholder="__('Token proporcionado por tu contador')"
        />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Crear cuenta') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
        {{ __('Ya tienes una cuenta?') }}
        <flux:link :href="route('login')" wire:navigate>{{ __('Inicia sesion') }}</flux:link>
    </div>
</div>
