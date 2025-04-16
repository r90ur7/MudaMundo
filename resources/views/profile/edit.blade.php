<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Perfil') }}
        </h2>
    </x-slot>
    <div class="py-12" x-data="{ activeTab: 'account' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Informações do perfil do usuário -->
            <div class="bg-white dark:bg-gray-900 shadow-lg rounded-lg overflow-hidden mb-6">
                <div class="p-6 flex flex-col md:flex-row items-center">
                    <div class="flex flex-col items-center md:items-start md:mr-8">
                        <div class="h-32 w-32 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center text-4xl text-gray-500 dark:text-gray-300 mb-4">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <button class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                            Trocar Foto
                        </button>
                    </div>
                    <div class="text-center md:text-left mt-4 md:mt-0 flex-grow">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ Auth::user()->name }}</h2>
                        <p class="text-gray-600 dark:text-gray-400">{{ Auth::user()->email }}</p>

                        <!-- Estatísticas do usuário -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Mudas Doadas</h3>
                                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">0</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Mudas Recebidas</h3>
                                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">0</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Mudas Cadastradas</h3>
                                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">0</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Abas do perfil -->
            <div class="bg-white dark:bg-gray-900 shadow-lg rounded-lg overflow-hidden">
                <div class="flex border-b border-gray-200 dark:border-gray-700">
                    <button @click.prevent="activeTab = 'account'"
                        class="px-6 py-3 font-semibold transition-all"
                        :class="{'text-emerald-500 border-b-2 border-emerald-500': activeTab === 'account', 'text-gray-700 dark:text-gray-300': activeTab !== 'account'}">
                        Minha Conta
                    </button>
                    <button @click.prevent="activeTab = 'mudas'"
                        class="px-6 py-3 font-semibold transition-all"
                        :class="{'text-emerald-500 border-b-2 border-emerald-500': activeTab === 'mudas', 'text-gray-700 dark:text-gray-300': activeTab !== 'mudas'}">
                        Mudas
                    </button>
                    <button @click.prevent="activeTab = 'chats'"
                        class="px-6 py-3 font-semibold transition-all"
                        :class="{'text-emerald-500 border-b-2 border-emerald-500': activeTab === 'chats', 'text-gray-700 dark:text-gray-300': activeTab !== 'chats'}">
                        Chats
                    </button>
                    <button @click.prevent="activeTab = 'historico'"
                        class="px-6 py-3 font-semibold transition-all"
                        :class="{'text-emerald-500 border-b-2 border-emerald-500': activeTab === 'historico', 'text-gray-700 dark:text-gray-300': activeTab !== 'historico'}">
                        Histórico
                    </button>
                </div>

                <div class="p-6">
                    <div x-show="activeTab === 'account'" class="space-y-6">
                        <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-lg shadow">
                            @include('profile.partials.update-profile-information-form')
                        </div>

                        <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-lg shadow">
                            @include('profile.partials.update-password-form')
                        </div>

                        <div class="p-6 bg-red-50 dark:bg-gray-800 dark:text-red-100 rounded-lg shadow">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>

                    <!-- Aba Mudas -->
                    <div x-show="activeTab === 'mudas'" class="space-y-6">
                        <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-lg shadow">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Minhas Mudas</h3>

                            <div class="space-y-4">
                                <p class="text-gray-600 dark:text-gray-400 italic">Você ainda não cadastrou nenhuma muda.</p>

                                <div class="mt-4">
                                    <a href="{{ route('mudas.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Cadastrar Nova Muda
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Aba Chats -->
                    <div x-show="activeTab === 'chats'" class="space-y-6">
                        <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-lg shadow">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Minhas Conversas</h3>

                            <div class="space-y-4">
                                <p class="text-gray-600 dark:text-gray-400 italic">Você não tem nenhuma conversa ativa no momento.</p>

                                <!-- Lista de conversas (será preenchida dinamicamente) -->
                                <div class="space-y-2">
                                    <!-- Exemplo de conversa -->
                                    <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow hover:bg-gray-50 dark:hover:bg-gray-600 transition cursor-pointer">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 bg-gray-200 dark:bg-gray-800 rounded-full flex items-center justify-center text-sm text-gray-500 dark:text-gray-300 mr-3">
                                                    M
                                                </div>
                                                <div>
                                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100">Maria Silva</h4>
                                                    <p class="text-sm text-gray-600 dark:text-gray-300">Interesse na Jabuticabeira</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-xs text-gray-500 dark:text-gray-400">10/04/2025</span>
                                                <div class="mt-1">
                                                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500">
                                                        <span class="text-xs font-medium text-white">2</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Aba Histórico -->
                    <div x-show="activeTab === 'historico'" class="space-y-6">
                        <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-lg shadow">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Histórico de Atividades</h3>

                            <div class="space-y-4">
                                <p class="text-gray-600 dark:text-gray-400 italic">Nenhuma atividade registrada ainda.</p>

                                <!-- Exemplo de como seria um item de histórico -->
                                <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow">
                                    <div class="flex justify-between items-center mb-2">
                                        <h4 class="font-semibold text-gray-900 dark:text-gray-100">Doação de Muda</h4>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">10/04/2025</span>
                                    </div>
                                    <p class="text-gray-600 dark:text-gray-300">Você doou uma muda de Jabuticabeira para Maria.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
