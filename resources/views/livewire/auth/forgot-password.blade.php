<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        Password::sendResetLink($this->only('email'));

        session()->flash('status', __('Se enviara un email con las instrucciones para restablecer tu contraseña si la cuenta existe.'));
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Restablecer contraseña')" :description="__('Ingresa tu correo para poder restablecer tu contraseña')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="flex flex-col gap-6">
        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Correo')"
            type="email"
            required
            autofocus
            placeholder="email@example.com"
            viewable
        />

        <flux:button variant="primary" type="submit" class="w-full">{{ __('Enviar correo') }}</flux:button>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-400">
        {{ __('O, regresar a') }}
        <flux:link :href="route('login')" wire:navigate>{{ __('iniciar sesion') }}</flux:link>
    </div>
</div>
