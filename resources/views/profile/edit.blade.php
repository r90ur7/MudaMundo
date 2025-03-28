<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Perfil') }}
        </h2>
    </x-slot>

    <!-- Container com Alpine.js para compartilhar o estado activeTab -->
    <div class="py-12" x-data="{ activeTab: 'account' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex bg-white dark:bg-gray-900 shadow-lg rounded-lg overflow-hidden">
            <!-- Sidebar (abas laterais) -->
            <aside class="w-1/4 bg-gray-100 dark:bg-gray-800 p-6">
                <nav class="space-y-2">
                    <button @click.prevent="activeTab = 'account'"
                        class="w-full px-4 py-3 text-left rounded-lg font-semibold transition-all"
                        :class="{'bg-blue-500 text-white': activeTab === 'account', 'text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700': activeTab !== 'account'}">
                        Minha Conta
                    </button>
                    <button @click.prevent="activeTab = 'mudas-chats'"
                        class="w-full px-4 py-3 text-left rounded-lg font-semibold transition-all"
                        :class="{'bg-blue-500 text-white': activeTab === 'mudas-chats', 'text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700': activeTab !== 'mudas-chats'}">
                        Mudas e Chats
                    </button>
                </nav>
            </aside>

            <!-- Conteúdo das abas -->
            <main class="w-3/4 p-6">
                <!-- Aba Minha Conta -->
                <div x-show="activeTab === 'account'" class="space-y-6">
                    <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-lg shadow">
                        @include('profile.partials.update-profile-information-form')
                    </div>

                    <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-lg shadow">
                        @include('profile.partials.update-password-form')
                    </div>

                    <div class="p-6 bg-red-100  bg-gray-50 dark:bg-gray-800 dark:text-red-100 rounded-lg shadow">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>

                <!-- Aba Mudas e Chats -->
                <div x-show="activeTab === 'mudas-chats'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Mudas Cadastradas -->
                    <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Mudas Cadastradas</h3>
                        <ul class="mt-4 space-y-2">
                            <li class="text-gray-600 dark:text-gray-300">
                                <strong>Muda 1:</strong> Jabuticabeira - Plantada em 2021
                            </li>
                            <li class="text-gray-600 dark:text-gray-300">
                                <strong>Muda 2:</strong> Pitangueira - Plantada em 2020
                            </li>
                            <li class="text-gray-600 dark:text-gray-300">
                                <strong>Muda 3:</strong> Mangueira - Plantada em 2019
                            </li>
                        </ul>
                    </div>

                    <!-- Mudas dos Usuários e Chats Ativos -->
                    <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Mudas dos Usuários e Chats Ativos</h3>
                        <ul class="mt-4 space-y-2">
                            <li class="text-gray-600 dark:text-gray-300">
                                <strong>Usuário João:</strong> Muda de Acerola - Chat ativo desde 01/10/2023
                            </li>
                            <li class="text-gray-600 dark:text-gray-300">
                                <strong>Usuário Maria:</strong> Muda de Goiaba - Chat ativo desde 15/09/2023
                            </li>
                            <li class="text-gray-600 dark:text-gray-300">
                                <strong>Usuário Pedro:</strong> Muda de Limão - Chat ativo desde 20/08/2023
                            </li>
                        </ul>
                    </div>
                </div>
            </main>
        </div>
    </div>
</x-app-layout>
