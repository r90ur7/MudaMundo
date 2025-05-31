<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Perfil') }}
        </h2>
    </x-slot>
    <div class="py-12" x-data="{ activeTab: 'account', showChatNotification: false, notificationChatId: null, notificationChatData: null, showChatModal: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 shadow-lg rounded-lg overflow-hidden mb-6">
                <div class="p-6 flex flex-col md:flex-row items-center">
                    <div class="flex flex-col items-center md:items-start md:mr-8">
                        <div class="h-32 w-32 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center text-4xl text-gray-500 dark:text-gray-300 mb-4 overflow-hidden">
                            @if(Auth::user()->foto_url)
                                <img src="{{ route('profile.photo', ['filename' => Auth::user()->foto_url]) }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                            @else
                                {{ substr(Auth::user()->name, 0, 1) }}
                            @endif
                        </div>
                        <button type="button" id="openProfilePhotoModal" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                            Trocar Foto
                        </button>
                    </div>
                    <div class="text-center md:text-left mt-4 md:mt-0 flex-grow">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ Auth::user()->name }}</h2>
                        <p class="text-gray-600 dark:text-gray-400">{{ Auth::user()->email }}</p>

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
                    <button @click.prevent="activeTab = 'solicitacoes'"
                        class="px-6 py-3 font-semibold transition-all"
                        :class="{'text-emerald-500 border-b-2 border-emerald-500': activeTab === 'solicitacoes', 'text-gray-700 dark:text-gray-300': activeTab !== 'solicitacoes'}">
                        Minhas Solicitações
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

                        <div class="mb-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="lgpd_consent" value="1" {{ old('lgpd_consent', $user->lgpd_consent ?? false) ? 'checked' : '' }} required>
                                <span class="ml-2">Li e concordo com os <a href="{{ url('/terms') }}" target="_blank" class="underline text-emerald-600">Termos de Serviço e Política de Privacidade</a></span>
                            </label>
                        </div>
                    </div>

                    <div x-show="activeTab === 'mudas'" class="space-y-6">
                        <div class="flex flex-col md:flex-row">
                            @include('profile.partials.sidebar-filtros', ['tipos' => $tipos, 'estados' => $estados])
                            <div class="flex-1" id="mudas-cards-container">
                                @include('mudas.partials.cards', ['mudas' => $mudas])
                            </div>
                        </div>
                    </div>

                    <div x-show="activeTab === 'chats'" class="space-y-6">
                        <div class="p-0 bg-transparent rounded-lg shadow-none">
                            <div class="flex flex-row gap-6" x-data="chatList()" x-init="init()">
                                <!-- Lista de conversas -->
                                <div class="w-1/3 min-w-[280px] max-w-[350px] bg-white dark:bg-gray-900 rounded-2xl shadow-lg border border-emerald-100 dark:border-emerald-800 overflow-hidden flex flex-col h-[600px]">
                                    <div class="px-4 py-3 bg-emerald-600 dark:bg-emerald-800 text-white flex items-center gap-2">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 12A4 4 0 1 1 8 12a4 4 0 0 1 8 0z"/></svg>
                                        <span class="font-bold text-lg">Minhas Conversas</span>
                                    </div>
                                    <div class="flex-1 overflow-y-auto p-2 custom-scrollbar" id="chats-list">
                                        @include('profile.partials.chats')
                                    </div>
                                </div>
                                <!-- Área do chat ou mensagem de seleção -->
                                <div class="flex-1 flex flex-col bg-gray-100 dark:bg-gray-900 rounded-2xl shadow-lg border border-emerald-100 dark:border-emerald-800 overflow-hidden h-[600px] relative animate-fade-in" id="chat-area">
                                    <template x-if="activeChat">
                                        <div class="flex flex-col h-full">
                                            <!-- Header do chat -->
                                            <div class="flex items-center gap-3 px-4 py-3 bg-emerald-600 dark:bg-emerald-800 text-white shadow">
                                                <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/20">
                                                    <span x-text="activeChat.otherUserInitial"></span>
                                                </div>
                                                <div>
                                                    <div class="font-bold text-lg" x-text="activeChat.otherUserName"></div>
                                                    <div class="text-xs opacity-80" x-text="activeChat.mudaNome"></div>
                                                </div>
                                                <div class="ml-auto flex gap-2">
                                                    <button @click="closeChat" class="hover:bg-emerald-700 p-2 rounded-full transition"><svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 18L18 6M6 6l12 12'/></svg></button>
                                                </div>
                                            </div>
                                            <!-- Mensagens -->
                                            <div class="flex-1 overflow-y-auto px-4 py-6 space-y-3 custom-scrollbar bg-[url('/image/whatsapp-bg.png')] bg-cover" id="chat-messages">
                                                <template x-for="msg in messages" :key="msg.local_id || msg.id">
                                                    <div :class="msg.user_id === userId ? 'flex items-end gap-2 justify-end' : 'flex items-end gap-2'">
                                                        <template x-if="msg.user_id !== userId">
                                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-200 dark:bg-emerald-700 text-emerald-900 dark:text-emerald-100"><span x-text="msg.user?.name ? msg.user.name[0] : '?' "></span></span>
                                                        </template>
                                                        <div :class="msg.user_id === userId ? 'bg-emerald-500 text-white rounded-2xl rounded-br-sm border border-emerald-400' : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-2xl rounded-bl-sm border border-gray-200 dark:border-gray-700'" class="px-4 py-2 shadow max-w-[70%]">
                                                            <span x-text="msg.mensagem"></span>
                                                            <div class="text-xs mt-1 text-right" :class="msg.user_id === userId ? 'text-white/70' : 'text-gray-400'" x-text="formatDate(msg.created_at)"></div>
                                                        </div>
                                                        <template x-if="msg.user_id === userId">
                                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-500 text-white"><span x-text="msg.user?.name ? msg.user.name[0] : '?' "></span></span>
                                                        </template>
                                                    </div>
                                                </template>
                                            </div>
                                            <!-- Input de mensagem -->
                                            <form @submit.prevent="sendMessage" class="flex items-center gap-2 px-4 py-3 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                                                <input x-model="newMessage" type="text" class="flex-1 rounded-full px-4 py-2 border border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-400 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100" placeholder="Digite uma mensagem..." autocomplete="off" :disabled="sendingMessage" />
                                                <button type="submit" class="p-2 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white transition" :disabled="sendingMessage || !newMessage.trim()"><svg xmlns='http://www.w3.org/2000/svg' class='h-6 w-6' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'/></svg></button>
                                            </form>
                                        </div>
                                    </template>
                                    <template x-if="!activeChat">
                                        <div class="flex flex-1 items-center justify-center h-full text-gray-400 dark:text-gray-500">
                                            <div class="flex flex-col items-center gap-4">
                                                <svg class="w-16 h-16 text-emerald-400 opacity-60" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                                <span class="text-lg">Selecione uma conversa para começar</span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div x-show="activeTab === 'historico'" class="space-y-6">
                        <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-lg shadow">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Histórico de Atividades</h3>
                            <div x-data="{ kanbanView: true, activeSection: null }">
                                <!-- Kanban Overview -->
                                <div x-show="kanbanView" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                    <!-- Card: Mudas Cadastradas -->
                                    <div @click="kanbanView = false; activeSection = 'cadastradas'" class="cursor-pointer rounded-xl border border-emerald-200 dark:border-emerald-700 bg-emerald-50/40 dark:bg-emerald-900/30 p-6 flex flex-col items-center hover:shadow-lg transition">
                                        <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-emerald-200 dark:bg-emerald-700 text-emerald-900 dark:text-emerald-100 mb-2"><svg xmlns='http://www.w3.org/2000/svg' class='h-7 w-7' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 4v16m8-8H4'/></svg></span>
                                        <h4 class="font-semibold text-lg text-emerald-700 dark:text-emerald-200">Mudas Cadastradas</h4>
                                        <span class="text-3xl font-bold text-emerald-900 dark:text-emerald-100 mt-2">{{$mudasCadastradas->count()}}</span>
                                    </div>
                                    <!-- Card: Mudas Doadas -->
                                    <div @click="kanbanView = false; activeSection = 'doadas'" class="cursor-pointer rounded-xl border border-emerald-200 dark:border-emerald-700 bg-emerald-50/40 dark:bg-emerald-900/30 p-6 flex flex-col items-center hover:shadow-lg transition">
                                        <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-emerald-200 dark:bg-emerald-700 text-emerald-900 dark:text-emerald-100 mb-2"><svg xmlns='http://www.w3.org/2000/svg' class='h-7 w-7' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'/></svg></span>
                                        <h4 class="font-semibold text-lg text-emerald-700 dark:text-emerald-200">Mudas Doadas</h4>
                                        <span class="text-3xl font-bold text-emerald-900 dark:text-emerald-100 mt-2">{{$mudasDoadas->count()}}</span>
                                    </div>
                                    <!-- Card: Solicitações Enviadas -->
                                    <div @click="kanbanView = false; activeSection = 'enviadas'" class="cursor-pointer rounded-xl border border-blue-200 dark:border-blue-700 bg-blue-50/40 dark:bg-blue-900/30 p-6 flex flex-col items-center hover:shadow-lg transition">
                                        <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-200 dark:bg-blue-700 text-blue-900 dark:text-blue-100 mb-2">
                                            <svg xmlns='http://www.w3.org/2000/svg' class='h-7 w-7' fill='none' viewBox='0 0 24 24' stroke='currentColor'>
                                                <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M14 5l7 7-7 7M5 12h16'/>
                                            </svg>
                                        </span>
                                        <h4 class="font-semibold text-lg text-blue-700 dark:text-blue-200">Solicitações Enviadas</h4>
                                        <span class="text-3xl font-bold text-blue-900 dark:text-blue-100 mt-2">{{$solicitacoesEnviadas->count()}}</span>
                                    </div>
                                    <!-- Card: Solicitações Recebidas -->
                                    <div @click="kanbanView = false; activeSection = 'recebidas'" class="cursor-pointer rounded-xl border border-purple-200 dark:border-purple-700 bg-purple-50/40 dark:bg-purple-900/30 p-6 flex flex-col items-center hover:shadow-lg transition">
                                        <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-purple-200 dark:bg-purple-700 text-purple-900 dark:text-purple-100 mb-2"><svg xmlns='http://www.w3.org/2000/svg' class='h-7 w-7' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'/></svg>
                                        </span>
                                        <h4 class="font-semibold text-lg text-purple-700 dark:text-purple-200">Solicitações Recebidas</h4>
                                        <span class="text-3xl font-bold text-purple-900 dark:text-purple-100 mt-2">{{$solicitacoesRecebidas->count()}}</span>
                                    </div>
                                    <!-- Card: Solicitações Rejeitadas -->
                                    <div @click="kanbanView = false; activeSection = 'rejeitadas'" class="cursor-pointer rounded-xl border border-red-200 dark:border-red-700 bg-red-50/40 dark:bg-red-900/30 p-6 flex flex-col items-center hover:shadow-lg transition">
                                        <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-red-200 dark:bg-red-700 text-red-900 dark:text-red-100 mb-2"><svg xmlns='http://www.w3.org/2000/svg' class='h-7 w-7' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 18L18 6M6 6l12 12'/></svg></span>
                                        <h4 class="font-semibold text-lg text-red-700 dark:text-red-200">Solicitações Rejeitadas</h4>
                                        <span class="text-3xl font-bold text-red-900 dark:text-red-100 mt-2">{{$solicitacoesRejeitadas->count()}}</span>
                                    </div>
                                </div>
                                <!-- Detalhe de cada seção -->
                                <div x-show="!kanbanView" class="flex justify-center items-start min-h-[400px]">
                                    <div class="relative w-full max-w-2xl">
                                        <!-- Botão Voltar (centralizado e maior) -->
                                        <div class="flex justify-center mb-2">
                                            <button @click="kanbanView = true; activeSection = null" class="flex items-center gap-3 px-8 py-3 bg-gray-100 dark:bg-gray-800 border-2 border-emerald-400 dark:border-emerald-700 shadow-lg rounded-xl text-lg font-semibold text-emerald-700 dark:text-emerald-200 hover:bg-emerald-50 dark:hover:bg-emerald-900 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                                                <svg xmlns='http://www.w3.org/2000/svg' class='h-7 w-7' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M15 19l-7-7 7-7'/></svg>
                                                Voltar ao Kanban
                                            </button>
                                        </div>

                                        <!-- Mudas Cadastradas Detalhe -->
                                        <div x-show="activeSection === 'cadastradas'" class="rounded-2xl border-4 border-emerald-300 dark:border-emerald-700 bg-white dark:bg-gray-900 shadow-2xl p-8 pt-14 animate-fade-in">
                                            <div class="flex items-center gap-3 mb-6">
                                                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-emerald-200 dark:bg-emerald-700 text-emerald-900 dark:text-emerald-100 text-2xl"><svg xmlns='http://www.w3.org/2000/svg' class='h-7 w-7' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 4v16m8-8H4'/></svg></span>
                                                <h4 class="font-bold text-2xl text-emerald-700 dark:text-emerald-200">Mudas Cadastradas</h4>
                                                <span class="ml-auto text-lg font-semibold text-emerald-900 dark:text-emerald-100 bg-emerald-100 dark:bg-emerald-800 px-3 py-1 rounded-full">{{$mudasCadastradas->count()}} mudas</span>
                                            </div>
                                            <div class="divide-y divide-emerald-100 dark:divide-emerald-800 max-h-80 overflow-y-auto pr-2 custom-scrollbar">
                                            @forelse($mudasCadastradas as $muda)
                                                <div class="py-3 flex items-center gap-3">
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-800 text-emerald-700 dark:text-emerald-200"><svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 4v16m8-8H4'/></svg></span>
                                                    <div class="flex-1">
                                                        <div class="flex flex-wrap gap-2 items-center mb-1">
                                                            <span class="font-bold">{{ $muda->nome }}</span>
                                                            <span class="text-xs px-2 py-0.5 rounded bg-emerald-200 dark:bg-emerald-700 text-emerald-900 dark:text-emerald-100">Tipo: {{ $muda->tipo->nome ?? '-' }}</span>
                                                        </div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400 flex flex-wrap gap-4">
                                                            <span>Cadastrada em: {{ $muda->created_at->format('d/m/Y H:i') }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <p class="text-gray-500 italic">Nenhuma muda cadastrada.</p>
                                            @endforelse
                                            </div>
                                        </div>
                                        <!-- Mudas Doadas Detalhe -->
                                        <div x-show="activeSection === 'doadas'" class="rounded-2xl border-4 border-emerald-300 dark:border-emerald-700 bg-white dark:bg-gray-900 shadow-2xl p-8 pt-14 animate-fade-in">
                                            <div class="flex items-center gap-3 mb-6">
                                                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-emerald-200 dark:bg-emerald-700 text-emerald-900 dark:text-emerald-100 text-2xl"><svg xmlns='http://www.w3.org/2000/svg' class='h-7 w-7' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'/></svg></span>
                                                <h4 class="font-bold text-2xl text-emerald-700 dark:text-emerald-200">Mudas Doadas</h4>
                                                <span class="ml-auto text-lg font-semibold text-emerald-900 dark:text-emerald-100 bg-emerald-100 dark:bg-emerald-800 px-3 py-1 rounded-full">{{$mudasDoadas->count()}} mudas</span>
                                            </div>
                                            <div class="divide-y divide-emerald-100 dark:divide-emerald-800 max-h-80 overflow-y-auto pr-2 custom-scrollbar">
                                            @forelse($mudasDoadas as $muda)
                                                <div class="py-3 flex items-center gap-3">
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-800 text-emerald-700 dark:text-emerald-200"><svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'/></svg></span>
                                                    <div class="flex-1">
                                                        <div class="flex flex-wrap gap-2 items-center mb-1">
                                                            <span class="font-bold">{{ $muda->nome }}</span>
                                                            <span class="text-xs px-2 py-0.5 rounded bg-emerald-200 dark:bg-emerald-700 text-emerald-900 dark:text-emerald-100">Tipo: {{ $muda->tipo->nome ?? '-' }}</span>
                                                        </div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400 flex flex-wrap gap-4">
                                                            <span>Doadas para: <span class="font-semibold text-emerald-700 dark:text-emerald-200">{{ $muda->donated_to->name ?? '-' }}</span></span>
                                                            <span>Data da doação: {{ $muda->donated_at ? $muda->donated_at->format('d/m/Y H:i') : '-' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <p class="text-gray-500 italic">Nenhuma muda doada.</p>
                                            @endforelse
                                            </div>
                                        </div>
                                        <!-- Solicitações Enviadas Detalhe -->
                                        <div x-show="activeSection === 'enviadas'" class="rounded-2xl border-4 border-blue-300 dark:border-blue-700 bg-white dark:bg-gray-900 shadow-2xl p-8 pt-14 animate-fade-in">
                                            <div class="flex items-center gap-3 mb-6">
                                                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-200 dark:bg-blue-700 text-blue-900 dark:text-blue-100 text-2xl">
                                                    <svg xmlns='http://www.w3.org/2000/svg' class='h-7 w-7' fill='none' viewBox='0 0 24 24' stroke='currentColor'>
                                                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M14 5l7 7-7 7M5 12h16'/>
                                                    </svg>
                                                </span>
                                                <h4 class="font-bold text-2xl text-blue-700 dark:text-blue-200">Solicitações Enviadas</h4>
                                                <span class="ml-auto text-lg font-semibold text-blue-900 dark:text-blue-100 bg-blue-100 dark:bg-blue-800 px-3 py-1 rounded-full">{{$solicitacoesEnviadas->count()}} solicitações</span>
                                            </div>
                                            <div class="divide-y divide-blue-100 dark:divide-blue-800 max-h-80 overflow-y-auto pr-2 custom-scrollbar">
                                            @forelse($solicitacoesEnviadas as $sol)
                                                <div class="py-3 flex items-center gap-3">
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-800 text-blue-700 dark:text-blue-200">
                                                        <svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5' fill='none' viewBox='0 0 24 24' stroke='currentColor'>
                                                            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M14 5l7 7-7 7M5 12h16'/>
                                                        </svg>
                                                    </span>
                                                    <div class="flex-1">
                                                        <div class="flex flex-wrap gap-2 items-center mb-1">
                                                            <span class="font-bold">{{ $sol->mudas->nome ?? '-' }}</span>
                                                            <span class="text-xs px-2 py-0.5 rounded bg-blue-200 dark:bg-blue-700 text-blue-900 dark:text-blue-100">Status: {{ $sol->status->nome ?? '-' }}</span>
                                                        </div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400 flex flex-wrap gap-4">
                                                            <span>Enviado para: <span class="font-semibold text-blue-700 dark:text-blue-200">{{ $sol->mudas->user->name ?? '-' }}</span></span>
                                                            <span>Data: {{ $sol->created_at->format('d/m/Y H:i') }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <p class="text-gray-500 italic">Nenhuma solicitação enviada.</p>
                                            @endforelse
                                            </div>
                                        </div>
                                        <!-- Solicitações Recebidas Detalhe -->
                                        <div x-show="activeSection === 'recebidas'" class="rounded-2xl border-4 border-purple-300 dark:border-purple-700 bg-white dark:bg-gray-900 shadow-2xl p-8 pt-14 animate-fade-in">
                                            <div class="flex items-center gap-3 mb-6">
                                                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-purple-200 dark:bg-purple-700 text-purple-900 dark:text-purple-100 text-2xl"><svg xmlns='http://www.w3.org/2000/svg' class='h-7 w-7' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'/></svg>
                                                </span>
                                                <h4 class="font-bold text-2xl text-purple-700 dark:text-purple-200">Solicitações Recebidas</h4>
                                                <span class="ml-auto text-lg font-semibold text-purple-900 dark:text-purple-100 bg-purple-100 dark:bg-purple-800 px-3 py-1 rounded-full">{{$solicitacoesRecebidas->count()}} solicitações</span>
                                            </div>
                                            <div class="divide-y divide-purple-100 dark:divide-purple-800 max-h-80 overflow-y-auto pr-2 custom-scrollbar">
                                            @forelse($solicitacoesRecebidas as $sol)
                                                <div class="py-3 flex items-center gap-3">
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-purple-100 dark:bg-purple-800 text-purple-700 dark:text-purple-200"><svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'/></svg>
                                                    </span>
                                                    <div class="flex-1">
                                                        <div class="flex flex-wrap gap-2 items-center mb-1">
                                                            <span class="font-bold">{{ $sol->mudas->nome ?? '-' }}</span>
                                                            <span class="text-xs px-2 py-0.5 rounded bg-purple-200 dark:bg-purple-700 text-purple-900 dark:text-purple-100">Status: {{ $sol->status->nome ?? '-' }}</span>
                                                        </div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400 flex flex-wrap gap-4">
                                                            <span>Solicitante: <span class="font-semibold text-purple-700 dark:text-purple-200">{{ $sol->user->name ?? '-' }}</span></span>
                                                            <span>Data: {{ $sol->created_at->format('d/m/Y H:i') }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <p class="text-gray-500 italic">Nenhuma solicitação recebida.</p>
                                            @endforelse
                                            </div>
                                        </div>
                                        <!-- Solicitações Rejeitadas Detalhe -->
                                        <div x-show="activeSection === 'rejeitadas'" class="rounded-2xl border-4 border-red-300 dark:border-red-700 bg-white dark:bg-gray-900 shadow-2xl p-8 pt-14 animate-fade-in">
                                            <div class="flex items-center gap-3 mb-6">
                                                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-red-200 dark:bg-red-700 text-red-900 dark:text-red-100 text-2xl"><svg xmlns='http://www.w3.org/2000/svg' class='h-7 w-7' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 18L18 6M6 6l12 12'/></svg></span>
                                                <h4 class="font-bold text-2xl text-red-700 dark:text-red-200">Solicitações Rejeitadas</h4>
                                                <span class="ml-auto text-lg font-semibold text-red-900 dark:text-red-100 bg-red-100 dark:bg-red-800 px-3 py-1 rounded-full">{{$solicitacoesRejeitadas->count()}} solicitações</span>
                                            </div>
                                            <div class="divide-y divide-red-100 dark:divide-red-800 max-h-80 overflow-y-auto pr-2 custom-scrollbar">
                                            @forelse($solicitacoesRejeitadas as $sol)
                                                <div class="py-3 flex items-center gap-3">
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-100 dark:bg-red-800 text-red-700 dark:text-red-200"><svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 18L18 6M6 6l12 12'/></svg></span>
                                                    <div class="flex-1">
                                                        <div class="flex flex-wrap gap-2 items-center mb-1">
                                                            <span class="font-bold">{{ $sol->mudas->nome ?? '-' }}</span>
                                                            <span class="text-xs px-2 py-0.5 rounded bg-red-200 dark:bg-red-700 text-red-900 dark:text-red-100">Status: {{ $sol->status->nome ?? '-' }}</span>
                                                        </div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400 flex flex-wrap gap-4">
                                                            <span>Solicitante: <span class="font-semibold text-red-700 dark:text-red-200">{{ $sol->user->name ?? '-' }}</span></span>
                                                            <span>Destinatário: <span class="font-semibold text-red-700 dark:text-red-200">{{ $sol->mudas->user->name ?? '-' }}</span></span>
                                                            <span>Rejeitada em: {{ $sol->rejected_at ? $sol->rejected_at->format('d/m/Y H:i') : '-' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <p class="text-gray-500 italic">Nenhuma solicitação rejeitada.</p>
                                            @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Aba Solicitações -->
                    <div x-show="activeTab === 'solicitacoes'" class="space-y-6">
                        <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-lg shadow">
                            @include('profile.partials.minhas-solicitacoes')
                        </div>
                    </div>

                    <!-- Exemplo de área de chat estilo WhatsApp -->
                    <div x-show="activeTab === 'chat'" class="flex flex-col h-[600px] max-w-2xl mx-auto bg-gray-100 dark:bg-gray-900 rounded-2xl shadow-lg border border-emerald-200 dark:border-emerald-700 overflow-hidden relative animate-fade-in">
                        <!-- Header do chat -->
                        <div class="flex items-center gap-3 px-4 py-3 bg-emerald-600 dark:bg-emerald-800 text-white shadow">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/20">
                                <svg xmlns='http://www.w3.org/2000/svg' class='h-7 w-7' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </span>
                            <div>
                                <div class="font-bold text-lg">Chat</div>
                                <div class="text-xs opacity-80">Online</div>
                            </div>
                            <div class="ml-auto flex gap-2">
                                <button class="hover:bg-emerald-700 p-2 rounded-full transition"><svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M3 10h18M3 6h18M3 14h18M3 18h18'/></svg></button>
                            </div>
                        </div>
                        <!-- Mensagens -->
                        <div class="flex-1 overflow-y-auto px-4 py-6 space-y-3 custom-scrollbar bg-[url('/image/whatsapp-bg.png')] bg-cover">
                            <!-- Mensagem recebida -->
                            <div class="flex items-end gap-2">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-200 dark:bg-emerald-700 text-emerald-900 dark:text-emerald-100"><svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 4v16m8-8H4'/></svg></span>
                                <div class="bg-white dark:bg-gray-800 rounded-2xl rounded-bl-sm px-4 py-2 shadow max-w-[70%] text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-700">
                                    <span>Olá! Como posso ajudar?</span>
                                    <div class="text-xs text-gray-400 mt-1 text-right">10:00</div>
                                </div>
                            </div>
                            <!-- Mensagem enviada -->
                            <div class="flex items-end gap-2 justify-end">
                                <div class="bg-emerald-500 text-white rounded-2xl rounded-br-sm px-4 py-2 shadow max-w-[70%] border border-emerald-400">
                                    <span>Oi! Gostaria de saber mais sobre as mudas.</span>
                                    <div class="text-xs text-white/70 mt-1 text-right">10:01</div>
                                </div>
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-500 text-white"><svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 4v16m8-8H4'/></svg></span>
                            </div>
                            <!-- Repita o bloco acima para cada mensagem, alternando a ordem/alinhamento -->
                        </div>
                        <!-- Input de mensagem -->
                        <div class="flex items-center gap-2 px-4 py-3 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                            <input type="text" class="flex-1 rounded-full px-4 py-2 border border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-400 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100" placeholder="Digite uma mensagem...">
                            <button class="p-2 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white transition"><svg xmlns='http://www.w3.org/2000/svg' class='h-6 w-6' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'/></svg></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="profilePhotoModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Trocar Foto de Perfil</h3>
                <button type="button" id="closeProfilePhotoModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            @if($errors->has('foto'))
                <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-md">
                    {{ $errors->first('foto') }}
                </div>
            @endif

            <form id="profilePhotoForm" method="post" action="{{ route('profile.update.photo') }}" class="space-y-4" enctype="multipart/form-data">
                @csrf
                @method('POST')

                <div class="space-y-2">
                    <label for="photo-upload" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Selecione uma nova foto
                    </label>
                    <input type="file" id="photo-upload" name="foto"
                        class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                        accept="image/*" required>
                    <p class="text-xs text-gray-500">Formatos aceitos: JPG, PNG. Tamanho máximo: 50MB.</p>
                </div>

                <div class="mt-4" id="preview-container" style="display: none;">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Preview:</p>
                    <div class="w-full flex justify-center">
                        <div class="w-32 h-32 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-700">
                            <img id="photo-preview" src="#" alt="Preview da foto" class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" id="cancelPhotoUpload" class="px-4 py-2 bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">
                        Salvar Foto
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if (session('status') === 'profile-photo-updated')
    <div id="photoSuccessNotification" class="fixed bottom-4 right-4 bg-emerald-500 text-white p-4 rounded-lg shadow-lg z-50">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <p>Foto de perfil atualizada com sucesso!</p>
        </div>
    </div>
    @endif

    <!-- Notificação global de chat (usando o mesmo comportamento do partial global) -->
    <x-chat-notification />

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const openModalBtn = document.getElementById('openProfilePhotoModal');
        const closeModalBtn = document.getElementById('closeProfilePhotoModal');
        const cancelBtn = document.getElementById('cancelPhotoUpload');
        const modal = document.getElementById('profilePhotoModal');
        const photoInput = document.getElementById('photo-upload');
        const photoPreview = document.getElementById('photo-preview');
        const previewContainer = document.getElementById('preview-container');

        function openModal() {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            document.getElementById('profilePhotoForm').reset();
            previewContainer.style.display = 'none';
        }

        if(openModalBtn) {
            openModalBtn.addEventListener('click', openModal);
        }

        if(closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
        if(cancelBtn) cancelBtn.addEventListener('click', closeModal);
        if(modal) {
            modal.addEventListener('click', function(e) {
                if(e.target === modal) closeModal();
            });
        }

        if(photoInput) {
            photoInput.addEventListener('change', function() {
                if(this.files && this.files[0]) {
                    const file = this.files[0];

                    // Verificar se o arquivo é uma imagem
                    if(!file.type.match('image.*')) {
                        alert('Por favor, selecione uma imagem.');
                        this.value = '';
                        return;
                    }

                    if(file.size > 50 * 1024 * 1024) {
                        alert('A imagem deve ter no máximo 50MB.');
                        this.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        photoPreview.src = e.target.result;
                        previewContainer.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                }
            });
        }

        const openDrawerBtn = document.getElementById('openDrawerBtn');
        const closeDrawerBtn = document.getElementById('closeDrawerBtn');
        const drawer = document.getElementById('drawerFiltros');
        if(openDrawerBtn && closeDrawerBtn && drawer) {
            openDrawerBtn.addEventListener('click', function() {
                drawer.classList.remove('hidden');
            });
            closeDrawerBtn.addEventListener('click', function() {
                drawer.classList.add('hidden');
            });
            drawer.addEventListener('click', function(e) {
                if(e.target === drawer) drawer.classList.add('hidden');
            });
        }
        const form = document.getElementById('filtros-mudas-form');
        const cardsContainer = document.getElementById('mudas-cards-container');
        if(form && cardsContainer) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                // Considerar filtro de tipo selecionado
                const formData = new FormData(form);
                const selectedFilter = document.querySelector('input[name="filter_type"]:checked');
                if (selectedFilter) formData.set('filter_type', selectedFilter.value);
                const params = new URLSearchParams(formData).toString();
                fetch('{{ route('profile.mudas.filter') }}?' + params, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.json())
                .then(data => {
                    cardsContainer.innerHTML = data.html;
                    window.history.replaceState({}, '', '{{ route('profile.edit') }}?' + params);
                });
            });

            // Auto-aplicar filtros ao mudar selects ou ao pressionar Enter na busca
            const tipoSelect = document.getElementById('tipo');
            const locationSelect = document.getElementById('location');
            const searchInput = document.getElementById('search');
            if(tipoSelect) tipoSelect.addEventListener('change', () => form.dispatchEvent(new Event('submit')));
            if(locationSelect) locationSelect.addEventListener('change', () => form.dispatchEvent(new Event('submit')));
            if(searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if(e.key === 'Enter') {
                        e.preventDefault();
                        form.dispatchEvent(new Event('submit'));
                    }
                });
            }

            // Aplicar filtros ao mudar rádio dentro do form (desktop)
            const desktopFilterRadios = document.querySelectorAll('input[name="filter_type"]');
            desktopFilterRadios.forEach(radio => {
                radio.addEventListener('change', () => {
                    const hidden = form.querySelector('input[name="filter_type"]');
                    if (hidden) hidden.value = radio.value;
                    form.dispatchEvent(new Event('submit'));
                });
            });
        }

        // filtros mobile
        const formMobile = document.getElementById('filtros-mudas-form-mobile');
        if(formMobile && cardsContainer) {
            formMobile.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(formMobile);
                const params = new URLSearchParams(formData).toString();
                fetch('{{ route('profile.mudas.filter') }}?' + params, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.json())
                .then(data => {
                    cardsContainer.innerHTML = data.html;
                    window.history.replaceState({}, '', '{{ route('profile.edit') }}?' + params);
                    drawer.classList.add('hidden');
                });
            });

            // Auto-aplicar filtros mobile ao mudar selects ou ao pressionar Enter na busca
            const tipoMobile = document.getElementById('tipo_mobile');
            const locationMobile = document.getElementById('location_mobile');
            const searchMobile = document.getElementById('search_mobile');
            if(tipoMobile) tipoMobile.addEventListener('change', () => formMobile.dispatchEvent(new Event('submit')));
            if(locationMobile) locationMobile.addEventListener('change', () => formMobile.dispatchEvent(new Event('submit')));
            if(searchMobile) {
                searchMobile.addEventListener('keypress', function(e) {
                    if(e.key === 'Enter') {
                        e.preventDefault();
                        formMobile.dispatchEvent(new Event('submit'));
                    }
                });
            }

            // Aplicar filtros ao mudar rádio dentro do form mobile
            const mobileFilterRadios = document.querySelectorAll('input[name="filter_type_mobile"]');
            mobileFilterRadios.forEach(radio => {
                radio.addEventListener('change', () => {
                    const hidden = formMobile.querySelector('input[name="filter_type"]');
                    if (hidden) hidden.value = radio.value;
                    formMobile.dispatchEvent(new Event('submit'));
                });
            });
        }

        // Auto-remover notificação de sucesso
        const photoSuccessNotification = document.getElementById('photoSuccessNotification');
        if (photoSuccessNotification) {
            setTimeout(() => {
                photoSuccessNotification.remove();
            }, 5000);
        }

        // Ao carregar a página, verifica se há hash para abrir chat específico
        if (window.location.hash && window.location.hash.startsWith('#chats-')) {
            const chatId = window.location.hash.replace('#chats-', '');
            setTimeout(() => {
                const chatList = document.querySelector('#chats-list');
                if (chatList && chatList.__x) {
                    const comp = chatList.__x.$data;
                    if (comp && typeof comp.openChat === 'function') {
                        comp.openChat(Number(chatId));
                    }
                }
            }, 500);
        }
    });

    window.openChatFromNotification = function(chatId, chatData) {
        // Ativa a aba de chats
        const root = document.querySelector('[x-data*="activeTab"]');
        if (root && root.__x) {
            root.__x.$data.activeTab = 'chats';
        }
        // Espera Alpine renderizar a aba de chats e abre o chat correto
        setTimeout(() => {
            const chatList = document.querySelector('#chats-list');
            if (chatList && chatList.__x) {
                const comp = chatList.__x.$data;
                if (comp && typeof comp.openChat === 'function') {
                    comp.openChat(Number(chatId));
                }
            }
        }, 400);
    };

    // --- INÍCIO: Atualizar lista de chats após nova solicitação ---
    window.addEventListener('solicitacao-criada', function() {
        // Aguarda Alpine inicializar
        setTimeout(() => {
            const chatList = document.querySelector('#chats-list');
            if (chatList && chatList.__x) {
                const comp = chatList.__x.$data;
                if (comp && typeof comp.fetchChats === 'function') {
                    comp.fetchChats();
                }
            }
        }, 300);
    });

    if (window.Echo) {
        window.Echo.private('user.' + {{ auth()->id() }})
            .listen('.App\\Events\\NovaMensagemChat', (e) => {
                if (e.mensagem) {
                    // Sempre dispara notificação, mesmo se for o próprio usuário
                    window.dispatchEvent(new CustomEvent('chat-notification', {
                        detail: {
                            chatId: e.mensagem.solicitacao_id,
                            chatData: {
                                otherUserName: e.mensagem.user.name,
                                preview: e.mensagem.mensagem
                            }
                        }
                    }));

                    // Atualiza a lista de chats em tempo real
                    const chatList = document.querySelector('#chats-list');
                    if (chatList && chatList.__x) {
                        const comp = chatList.__x.$data;
                        if (comp && typeof comp.fetchChats === 'function') {
                            comp.fetchChats();
                        }
                        // Se o chat modal está aberto para o mesmo chat, adiciona a mensagem em tempo real
                        if (comp && comp.activeChat && comp.activeChat.solicitacao.id === e.mensagem.solicitacao_id) {
                            // Evita duplicidade se já existe pelo local_id
                            const exists = comp.messages.some(m => m.id === e.mensagem.id);
                            if (!exists) {
                                comp.messages.push({ ...e.mensagem, status: 'sent' });
                                setTimeout(() => {
                                    const el = document.getElementById('chat-messages');
                                    if (el) el.scrollTop = el.scrollHeight;
                                }, 50);
                            }
                        }
                    }
                }
            });
    }
    </script>
    @endpush
</x-app-layout>
