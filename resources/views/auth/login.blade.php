<x-guest-layout>
    <div class="h-full">
        <div class="flex h-full">
            <!-- Image Section -->
            <div class="hidden lg:block relative w-full lg:w-1/2">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <img class="absolute inset-0 h-full w-full object-cover" src="{{'https://images.unsplash.com/photo-1518531933037-91b2f5f229cc?q=80&w=1944&auto=format&fit=crop' }}" alt="Plantas">
                <div class="relative z-10 px-8 py-12 h-full flex flex-col justify-between">
                    <a href="{{ route('home') }}">
                        <svg class="w-16 h-auto" width="80" height="80" viewBox="0 0 80 80" fill="none">
                            <image href="{{ asset('image/mudaMundo.png') }}" class="w-full h-full"/>
                        </svg>
                    </a>
                    <div class="space-y-4">
                        <h1 class="text-white text-4xl font-bold">Transforme o mundo, uma muda por vez</h1>
                        <p class="text-white/80 text-lg">Junte-se a nossa comunidade de amantes da natureza e faça parte desta mudança.</p>
                    </div>
                </div>
            </div>

            <!-- Form Section -->
            <div class="w-full lg:w-1/2">
                <div class="h-full p-8 lg:p-12">
                    <div class="flex justify-end">
                        <button type="button" class="mr-2 ml-4 hs-dark-mode-active:hidden block hs-dark-mode font-medium text-gray-800 rounded-full hover:bg-gray-200 focus:outline-hidden focus:bg-gray-200 dark:text-neutral-200 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800" data-hs-theme-click-value="dark">
                        <span class="group inline-flex shrink-0 justify-center items-center size-9">
                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path>
                            </svg>
                        </span>
                    </button>
                    <button type="button" class="mr-2 ml-4 hs-dark-mode-active:block hidden hs-dark-mode font-medium text-gray-800 rounded-full hover:bg-gray-200 focus:outline-hidden focus:bg-gray-200 dark:text-neutral-200 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800" data-hs-theme-click-value="light">
                        <span class="group inline-flex shrink-0 justify-center items-center size-9">
                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="4"></circle>
                            <path d="M12 2v2"></path>
                            <path d="M12 20v2"></path>
                            <path d="m4.93 4.93 1.41 1.41"></path>
                            <path d="m17.66 17.66 1.41 1.41"></path>
                            <path d="M2 12h2"></path>
                            <path d="M20 12h2"></path>
                            <path d="m6.34 17.66-1.41 1.41"></path>
                            <path d="m19.07 4.93-1.41 1.41"></path>
                            </svg>
                        </span>
                    </button>
                    </div>

                    <div class="mt-8 lg:mt-12">
                        <div class="space-y-6">
                            <h2 class="text-3xl font-bold text-gray-800 dark:text-white">Bem-vindo de volta</h2>
                            <p class="text-gray-600 dark:text-gray-400">Faça login na sua conta para continuar</p>
                        </div>

                        <!-- Session Status -->
                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <form class="mt-8 space-y-6" method="POST" action="{{ route('login') }}">
                            @csrf

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                <input id="email" name="email" type="email" :value="old('email')" required autofocus autocomplete="username"
                                    class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-700 shadow-sm dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:focus:border-emerald-500">
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Senha</label>
                                <input id="password" name="password" type="password" required autocomplete="current-password"
                                    class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-700 shadow-sm dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:focus:border-emerald-500">
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <input id="remember_me" name="remember" type="checkbox"
                                        class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900">
                                    <label for="remember_me" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Lembrar-me</label>
                                </div>

                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                        class="text-sm font-medium text-emerald-600 hover:text-emerald-500 dark:text-emerald-500 dark:hover:text-emerald-400">
                                        Esqueceu sua senha?
                                    </a>
                                @endif
                            </div>

                            <button type="submit"
                                class="flex w-full justify-center rounded-lg bg-emerald-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-900">
                                Entrar
                            </button>

                            <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                                Não tem uma conta?
                                <a href="{{ route('register') }}" class="font-medium text-emerald-600 hover:text-emerald-500 dark:text-emerald-500 dark:hover:text-emerald-400">
                                    Cadastre-se
                                </a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
