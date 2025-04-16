<x-app-layout>
    <div class="py-12 bg-neutral-800/40 dark:bg-neutral-200/40">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Mensagem de sucesso -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 rounded-lg flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <div class="bg-neutral-700/80 dark:bg-neutral-300/80 rounded-xl shadow-lg overflow-hidden">
                <!-- Cabeçalho com título e ações -->
                <div class="p-6 border-b border-neutral-600 dark:border-neutral-400">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl font-bold text-white dark:text-black">
                            {{ $muda->nome }}
                        </h1>
                        <div class="flex space-x-2">
                            <a href="{{ route('dashboard') }}"
                                class="text-emerald-500 hover:text-emerald-600 dark:text-emerald-600 dark:hover:text-emerald-700 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Voltar
                            </a>

                            @if(auth()->id() === $muda->user_id)
                                <a href="{{ route('mudas.edit', $muda) }}"
                                    class="text-yellow-500 hover:text-yellow-600 dark:text-yellow-600 dark:hover:text-yellow-700 flex items-center ml-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Editar
                                </a>

                                <form action="{{ route('mudas.destroy', $muda) }}" method="POST" class="inline" id="deleteForm">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            class="text-red-500 hover:text-red-600 dark:text-red-600 dark:hover:text-red-700 flex items-center ml-4"
                                            data-hs-overlay="#confirmDeleteModal">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Desabilitar
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Conteúdo principal -->
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Coluna da Imagem -->
                        <div class="lg:col-span-1">
                            <div class="bg-neutral-600/50 dark:bg-neutral-400/50 rounded-lg p-1">
                                @if($muda->foto_url)
                                    @php
                                        $filename = pathinfo($muda->foto_url, PATHINFO_BASENAME);
                                    @endphp
                                    <img src="{{ route('mudas.getFileImage', ['filename' => $filename]) }}"
                                        alt="{{ $muda->nome }}"
                                        class="w-full h-auto rounded-lg object-cover">
                                @else
                                    <div class="w-full h-72 flex flex-col items-center justify-center rounded-lg bg-neutral-700/50 dark:bg-neutral-200/50 text-center p-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-emerald-500/70 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3-3m0 0l3 3m-3-3v8" />
                                        </svg>
                                        <div class="flex items-center justify-center mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-emerald-600 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M14.828 14.828a4 4 0 10-5.656-5.656 4 4 0 005.656 5.656zM9.172 9.172A4 4 0 1014.828 14.828M3 12h6m12 0h-6" />
                                            </svg>
                                        </div>
                                        <p class="text-white dark:text-black font-medium">Ops! Esta plantinha é tímida</p>
                                        <p class="text-gray-300 dark:text-gray-600 text-sm mt-1">Não encontramos nenhuma foto para esta muda</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Informações rápidas -->
                            <div class="mt-6 space-y-4">
                                <div class="flex items-center justify-between p-3 bg-neutral-600/50 dark:bg-neutral-400/50 rounded-lg">
                                    <span class="text-sm font-medium text-white dark:text-black">Status</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $muda->status->nome === 'Disponível' ? 'bg-green-100 text-green-800' : ($muda->status->nome === 'Indisponível' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ $muda->status->nome ?? 'Indisponível' }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between p-3 bg-neutral-600/50 dark:bg-neutral-400/50 rounded-lg">
                                    <span class="text-sm font-medium text-white dark:text-black">Tipo</span>
                                    <span class="text-sm text-emerald-400 dark:text-emerald-600">{{ $muda->tipo->nome ?? 'Não especificado' }}</span>
                                </div>

                                <div class="flex items-center justify-between p-3 bg-neutral-600/50 dark:bg-neutral-400/50 rounded-lg">
                                    <span class="text-sm font-medium text-white dark:text-black">Espécie</span>
                                    <span class="text-sm text-emerald-400 dark:text-emerald-600">{{ $muda->especie->nome ?? 'Não especificado' }}</span>
                                </div>

                                <div class="flex items-center justify-between p-3 bg-neutral-600/50 dark:bg-neutral-400/50 rounded-lg">
                                    <span class="text-sm font-medium text-white dark:text-black">Quantidade</span>
                                    <span class="text-sm text-white dark:text-black">{{ $muda->quantidade ?? '1' }} unidade(s)</span>
                                </div>

                                <div class="flex items-center justify-between p-3 bg-neutral-600/50 dark:bg-neutral-400/50 rounded-lg">
                                    <span class="text-sm font-medium text-white dark:text-black">Cadastrado em</span>
                                    <span class="text-sm text-white dark:text-black">{{ $muda->created_at->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Coluna dos Detalhes -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Descrição -->
                            <div>
                                <h3 class="text-lg font-medium text-white dark:text-black mb-3">Descrição</h3>
                                <p class="text-sm text-gray-400 dark:text-gray-600">
                                    {{ $muda->descricao }}
                                </p>
                            </div>

                            <!-- Localização -->
                            <div>
                                <h3 class="text-lg font-medium text-white dark:text-black mb-3">Localização</h3>
                                <div class="bg-neutral-600/50 dark:bg-neutral-400/50 rounded-lg p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-400 dark:text-gray-600">Endereço</p>
                                            <p class="text-sm text-emerald-400 dark:text-emerald-600">
                                                {{ $muda->logradouro }}, {{ $muda->numero }}
                                                @if($muda->complemento)
                                                    , {{ $muda->complemento }}
                                                @endif
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-sm text-gray-400 dark:text-gray-600">Bairro</p>
                                            <p class="text-sm text-emerald-400 dark:text-emerald-600">{{ $muda->bairro }}</p>
                                        </div>

                                        <div>
                                            <p class="text-sm text-gray-400 dark:text-gray-600">Cidade/UF</p>
                                            <p class="text-sm text-emerald-400 dark:text-emerald-600">{{ $muda->cidade }}/{{ $muda->uf }}</p>
                                        </div>

                                        <div>
                                            <p class="text-sm text-gray-400 dark:text-gray-600">CEP</p>
                                            <p class="text-sm text-emerald-400 dark:text-emerald-600">
                                                {{ substr($muda->cep, 0, 5) }}-{{ substr($muda->cep, 5, 3) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Doador -->
                            <div>
                                <h3 class="text-lg font-medium text-white dark:text-black mb-3">Dono</h3>
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-emerald-500 flex items-center justify-center">
                                        <span class="text-white font-medium text-lg" >
                                            {{ strtoupper(substr($muda->user->name ?? 'Anônimo', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="ml-3">
                                        <p class=" text-gray-400 dark:text-gray-600 font-medium">
                                            {{ $muda->user->name ?? 'Usuário Anônimo' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Botões de Ação -->
                            <div class="pt-4">
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <a href="#" class="w-full inline-flex justify-center items-center gap-2 rounded-lg bg-emerald-600 font-semibold text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all text-sm py-3 px-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                        Entrar em Contato
                                    </a>

                                    <button type="button" class="w-full inline-flex justify-center items-center gap-2 rounded-lg border border-emerald-500/50 font-semibold text-emerald-500 hover:text-white hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all text-sm py-3 px-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                        Favoritar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmação de exclusão -->
    <div id="confirmDeleteModal" class="hs-overlay hidden w-full h-full fixed top-0 left-0 z-[60] overflow-x-hidden overflow-y-auto">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div class="flex flex-col bg-neutral-800 dark:bg-neutral-100 border border-neutral-700 dark:border-neutral-300 shadow-sm rounded-xl overflow-hidden">
                <!-- Header do modal -->
                <div class="p-4 sm:p-5 flex justify-between items-center border-b border-neutral-700 dark:border-neutral-300">
                    <h3 class="font-semibold text-xl text-white dark:text-black">
                        Confirmar exclusão
                    </h3>
                    <button type="button" class="flex items-center justify-center w-7 h-7 text-white dark:text-black rounded-full hover:bg-neutral-700 dark:hover:bg-neutral-300 focus:outline-none" data-hs-overlay="#confirmDeleteModal">
                        <span class="sr-only">Fechar</span>
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18"></path>
                            <path d="m6 6 12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Corpo do modal -->
                <div class="p-4 sm:p-6 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-red-100 dark:bg-red-700 mb-4">
                        <svg class="h-8 w-8 text-red-600 dark:text-red-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <h3 class="mb-2 text-lg font-semibold text-white dark:text-black">Desabilitar "{{ $muda->nome }}"?</h3>
                    <p class="text-gray-400 dark:text-gray-600">
                        Essa muda Ainda poderá ser reabilitada no menu minhas mudas
                    </p>
                </div>
                <!-- Footer com botões de ação -->
                <div class="flex justify-end items-center gap-x-2 py-3 px-4 sm:px-5 border-t border-neutral-700 dark:border-neutral-300">
                    <button type="button"
                            class="py-2.5 px-4 inline-flex justify-center items-center gap-2 rounded-lg border border-neutral-600 dark:border-neutral-400 font-medium text-white dark:text-black hover:bg-neutral-700 dark:hover:bg-neutral-300 focus:outline-none focus:ring-2 focus:ring-neutral-500 transition-all text-sm"
                            data-hs-overlay="#confirmDeleteModal">
                        Cancelar
                    </button>
                    <button type="button"
                            id="confirmDeleteBtn"
                            class="py-2.5 px-4 inline-flex justify-center items-center gap-2 rounded-lg border border-red-700 font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all text-sm">
                        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Excluir
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Inicializar o comportamento de excluir quando o documento estiver pronto
        document.addEventListener('DOMContentLoaded', function() {
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            const deleteForm = document.getElementById('deleteForm');

            if (confirmDeleteBtn && deleteForm) {
                confirmDeleteBtn.addEventListener('click', function() {
                    // Enviar o formulário de exclusão
                    deleteForm.submit();
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
