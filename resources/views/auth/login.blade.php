<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Username -->
        <div class="mt-4">
            <x-input-label for="name" :value="__('Username')" />
            <x-text-input id="name" class="block mt-1 w-full" 
                          type="text" 
                          name="name" 
                          :value="old('name')" 
                          required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" 
                       class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 
                              text-indigo-600 shadow-sm focus:ring-indigo-500 
                              dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" 
                       name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Remember me') }}
                </span>
            </label>
        </div>

        <!-- Tombol Login & Register -->
        <div class="flex justify-end items-center gap-4 mt-6">
            <a href="{{ route('register') }}">
                <button type="button"
                        class="bg-gray-600 hover:bg-gray-700 text-white 
                               font-semibold py-2 px-6 rounded text-base">
                    {{ __('Register') }}
                </button>
            </a>

            <button type="submit"
                    class="bg-gray-600 hover:bg-gray-700 text-white 
                           font-semibold py-2 px-6 rounded text-base">
                {{ __('Log in') }}
            </button>
        </div>
    </form>
</x-guest-layout>
