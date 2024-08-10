<x-guest-layout>
    @if (isset($error_restPassword))

    <div class="alert alert-danger mt-4 p-2" role="alert">
        {{$error_restPassword}}
    </div>

    @endif
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your mobile address and we will mobile you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.code') }}">
        @csrf

        <!-- Email Address -->
            <x-text-input id="Mobile" class="block selected text-secondary mt-1 w-full" type="hidden" name="mobile" :value="$mobile" required />
        <br>
        <div>
            <x-input-label for="Code" :value="__('Code (4 Digit)')" />
            <x-text-input id="Code" class="block mt-1 w-full" type="number" name="code" required autofocus />
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>

        @if(session()->has('error:restPassword'))

        <div class="alert alert-danger mt-4 p-2" role="alert">
            {{session()->get('error:restPassword')}}
        </div>

        @endif

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Mobile Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
