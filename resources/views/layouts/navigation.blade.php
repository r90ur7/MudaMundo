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
                    <!-- Ícone de notificação geral (mensagens + histórico) -->
                    <div x-data="{
    showDropdown: false,
    notifications: [],
    hasNew: false,
    setListeners() {
        const saved = localStorage.getItem('notificacoes-mudamundo');
        this.notifications = saved ? JSON.parse(saved).map(n => ({...n, created_at: new Date(n.created_at)})) : [];
        this.hasNew = this.notifications.some(n => !n.read);
        window.addEventListener('chat-notification', (e) => {
            const notification = {
                type: 'chat',
                chatId: e.detail.chatId,
                chatData: e.detail.chatData,
                read: false,
                created_at: new Date(),
            };
            this.notifications.unshift(notification);
            this.hasNew = true;
            this.saveNotifications();
        });
        window.addEventListener('history-notification', (e) => {
            const notification = {
                type: 'history',
                data: e.detail,
                read: false,
                created_at: new Date(),
            };
            this.notifications.unshift(notification);
            this.hasNew = true;
            this.saveNotifications();
        });
    },
    saveNotifications() {
        localStorage.setItem('notificacoes-mudamundo', JSON.stringify(this.notifications));
    },
    openDropdown() {
        this.showDropdown = true;
        this.hasNew = false;
        this.notifications.forEach(n => n.read = true);
        this.saveNotifications();
    },
    closeDropdown() {
        this.showDropdown = false;
    },
    handleClick(notification) {
        // Remove a notificação ao clicar
        this.notifications = this.notifications.filter(n => n !== notification);
        this.saveNotifications();
        if (notification.type === 'chat') {
            window.openChatFromNotification && window.openChatFromNotification(notification.chatId, notification.chatData);
        } else if (notification.type === 'history') {
            const root = document.querySelector('[x-data*=\'activeTab\']');
            if (root && root.__x) {
                root.__x.$data.activeTab = 'historico';
            }
        }
        this.closeDropdown();
    }
}"
x-init="setListeners()"
class="relative flex items-center mr-4 select-none">
                        <button @click="showDropdown ? closeDropdown() : openDropdown()" class="relative focus:outline-none" :class="{'animate-bounce': hasNew}" tabindex="0">
                            <!-- Ícone de sino para notificações gerais -->
                            <svg class="w-7 h-7 text-gray-500 dark:text-gray-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span x-show="hasNew" class="absolute top-0 right-0 block h-3 w-3 rounded-full ring-2 ring-white bg-emerald-500"></span>
                        </button>
                        <div x-show="showDropdown" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" @click.away="closeDropdown()" class="absolute left-1/2 -translate-x-1/2 top-full bg-white dark:bg-gray-800 rounded-lg shadow-lg z-50 border border-gray-200 dark:border-gray-700 w-80">
                            <div class="p-4 border-b border-gray-100 dark:border-gray-700 font-semibold text-gray-700 dark:text-gray-200">Notificações</div>
                            <template x-if="notifications.length === 0">
                                <div class="p-4 text-gray-400 text-center flex flex-col items-center gap-2">
                                    <svg class="w-10 h-10 mx-auto text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                    <span>Nenhuma notificação no momento</span>
                                </div>
                            </template>
                            <template x-for="(notification, idx) in notifications" :key="idx">
                                <div @click="handleClick(notification)" class="flex items-start gap-3 px-4 py-3 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 cursor-pointer border-b border-gray-100 dark:border-gray-700 last:border-b-0"
                                    :class="{'bg-emerald-50 dark:bg-emerald-900/30': !notification.read}">
                                    <template x-if="notification.type === 'chat'">
                                        <div class="flex-shrink-0 mt-1">
                                            <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                                        </div>
                                    </template>
                                    <template x-if="notification.type === 'history'">
                                        <div class="flex-shrink-0 mt-1">
                                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7-7 7M5 12h16"/>
                                            </svg>
                                        </div>
                                    </template>
                                    <div class="flex-1 min-w-0">
                                        <template x-if="notification.type === 'chat'">
                                            <div>
                                                <div class="font-semibold text-gray-800 dark:text-gray-100">Nova mensagem de <span x-text="notification.chatData.otherUserName"></span></div>
                                                <div class="text-gray-500 dark:text-gray-300 text-sm truncate" x-text="notification.chatData.preview"></div>
                                            </div>
                                        </template>
                                        <template x-if="notification.type === 'history'">
                                            <div>
                                                <div class="font-semibold text-gray-800 dark:text-gray-100">Nova atualização no histórico</div>
                                                <div class="text-gray-500 dark:text-gray-300 text-sm truncate" x-text="notification.data.preview"></div>
                                            </div>
                                        </template>
                                        <div class="text-xs text-gray-400 mt-1" x-text="notification.created_at.toLocaleString('pt-BR', { hour: '2-digit', minute: '2-digit', day: '2-digit', month: '2-digit' })"></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    <!-- Fim ícone de notificação geral -->
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
