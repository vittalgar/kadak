<!--
    File: resources/views/auth/login.blade.php
-->
<x-guest-layout>
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <!-- Logo -->
            <a href="/">
                <img class="mx-auto h-20 w-auto" src="{{ asset('bharath.png') }}" alt="Project Kadak">
            </a>

            <!-- Heading -->
            <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">
                Sign in to your account
            </h2>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form class="space-y-6" method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email
                        address</label>
                    <div class="mt-2">
                        <x-text-input id="email" name="email" type="email" autocomplete="email" required
                            :value="old('email')"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-[#042455] sm:text-sm sm:leading-6" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                    <div class="mt-2">
                        <x-text-input id="password" name="password" type="password" autocomplete="current-password"
                            required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-[#042455] sm:text-sm sm:leading-6" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me Checkbox -->
                <div class="block">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox"
                            class="h-4 w-4 rounded border-gray-300 text-[#042455] focus:ring-[#042455]" name="remember">
                        <span class="ms-2 text-sm text-gray-500">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <!-- Sign In Button -->
                <div>
                    <button type="submit"
                        class="flex w-full justify-center rounded-md bg-[#042455] px-3 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-[#031b40] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#042455]">
                        Sign in
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-guest-layout>
