<nav x-data="{ open: false }" class="relative max-w-5xl w-full py-4 px-6 md:flex md:items-center md:justify-between md:py-2 mx-2 lg:mx-auto rounded-lg bg-neutral-800/30 dark:bg-neutral-200/30 backdrop-blur-md shadow-lg">
    <div class="flex items-center justify-between w-full">
        <div class="shrink-0 flex gap-4 items-center">
            <a href="{{ route('dashboard') }}">
                <svg class="w-8 h-auto rounded-full overflow-hidden" width="15" height="15" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <image href="{{ asset('image/mudaMundo.png') }}" alt="MudaMundo" class="w-20 m-5 h-auto rounded-full" />
                </svg>
            </a>
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
        <div class="md:hidden">
            <button type="button" class="hs-collapse-toggle size-8 flex justify-center items-center text-sm font-semibold rounded-full bg-neutral-800 dark:bg-neutral-200 text-white dark:text-black disabled:opacity-50 disabled:pointer-events-none" id="hs-navbar-floating-dark-collapse" aria-controls="hs-navbar-floating-dark" data-hs-collapse="#hs-navbar-floating-dark">
                <!-- Ícones do menu mobile -->
            </button>

            <!-- Navigation Links -->
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-nav-link>
            </div>
        </div>
                    <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <!-- Ícone de notificação de chat -->
                    <div x-data="{
                            hasNewChat: false,
                            notificationChatId: null,
                            notificationChatData: null,
                            setListeners() {
                                window.addEventListener('chat-notification', (e) => {
                                    this.hasNewChat = true;
                                    this.notificationChatId = e.detail.chatId;
                                    this.notificationChatData = e.detail.chatData;
                                });
                                window.addEventListener('chat-notification-clear', () => {
                                    this.hasNewChat = false;
                                });
                            }
                        }"
                        x-init="setListeners()"
                        class="relative flex items-center mr-4">
                        <button
                            @click.prevent="hasNewChat = false; window.hasNewChatNotification = false; window.dispatchEvent(new Event('chat-notification-clear')); window.location.href = '{{ route('profile.edit') }}#chats'; $nextTick(() => { if (notificationChatId) { window.openChatFromNotification && window.openChatFromNotification(notificationChatId, notificationChatData); } })"
                            class="relative focus:outline-none"
                            :class="{'animate-bounce': hasNewChat}">
                            <svg class="w-7 h-7 text-gray-500 dark:text-gray-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                            </svg>
                            <span x-show="hasNewChat" class="absolute top-0 right-0 block h-3 w-3 rounded-full ring-2 ring-white bg-emerald-500"></span>
                        </button>
                    </div>
                    <!-- Fim ícone de notificação de chat -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                @if(Auth::user()->foto_url)
                                    <img src="{{ route('profile.photo', ['filename' => Auth::user()->foto_url]) }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full object-cover">
                                @else
                                    <span class="w-8 h-8 rounded-full bg-emerald-200 text-emerald-700 flex items-center justify-center font-bold">{{ mb_substr(Auth::user()->name, 0, 1) }}</span>
                                @endif
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Perfil') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Sair') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex space-x-4">
                        <a href="{{ route('login') }}"
                        class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-emerald-500 hover:bg-emerald-100 disabled:opacity-50 disabled:pointer-events-none">
                            Entrar
                        </a>
                        <a href="{{ route('register') }}"
                        class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-emerald-500 text-emerald-500 hover:bg-emerald-500 hover:text-white disabled:opacity-50 disabled:pointer-events-none">
                            Cadastrar
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Perfil') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Sair') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="space-y-1 px-4">
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Entrar') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')">
                        {{ __('Cadastrar') }}
                    </x-responsive-nav-link>
                </div>
            @endauth
        </div>
    </div>
</nav>
