<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Checkout da Solicitação') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Mensagem de erro ou sucesso -->
            <div id="mensagem-status" class="mb-6 hidden">
                <!-- Será preenchido via JavaScript -->
            </div>

            <div class="bg-white dark:bg-gray-900 shadow rounded-lg p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Resumo da Muda -->
                <div class="space-y-4">
                    @if($muda->foto_url)
                        @php($filename = pathinfo($muda->foto_url, PATHINFO_BASENAME))
                        <img src="{{ route('mudas.getFileImage', ['filename' => $filename]) }}" alt="{{ $muda->nome }}" class="w-full h-64 object-cover rounded">
                    @endif
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ $muda->nome }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Quantidade: {{ $muda->quantidade ?? 1 }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Localização: {{ $muda->cidade }}/{{ $muda->uf }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Tipo de Solicitação: <span class="font-semibold">{{ ucfirst($muda->modo_solicitacao ?? 'doacao') }}</span></p>
                </div>

                <!-- Formulário de Solicitação -->
                <form id="solicitacao-form" action="{{ route('solicitacoes.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="muda_id" value="{{ $muda->id }}">
                    <input type="hidden" name="solicitacao_tipos_id" value="{{ $muda->modo_solicitacao === 'permuta' ? 2 : 1 }}">

                    @if($muda->modo_solicitacao === 'permuta')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Escolha uma muda do seu inventário para trocar</label>
                            <input type="hidden" name="muda_troca_id" id="muda_troca_id">
                            <button type="button" id="open-mudas-modal" class="mt-1 px-3 py-2 bg-emerald-600 text-white rounded">Selecionar muda</button>
                            <div id="selected-muda" class="mt-2 text-sm text-gray-600 dark:text-gray-300"></div>
                            <div id="erro-muda_troca_id" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>
                    @endif

                    <div>
                        <label for="mensagem" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Mensagem de contato') }}</label>
                        <textarea id="mensagem" name="mensagem" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-700 dark:text-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Escreva uma mensagem opcional..."></textarea>
                        <div id="erro-mensagem" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <div class="flex justify-end">
                        <button
                            type="submit"
                            id="submit-button"
                            class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            @if($muda->modo_solicitacao === 'permuta') disabled @endif>
                            {{ __('Enviar Pedido') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de seleção de mudas -->
    <div id="mudas-modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-3xl w-full overflow-auto p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Selecione uma muda para permuta</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach(auth()->user()->mudas()->whereNull('disabled_at')->get() as $minhaMuda)
                    <div data-id="{{ $minhaMuda->id }}" data-foto="{{ $minhaMuda->foto_url ? route('mudas.getFileImage',['filename'=>pathinfo($minhaMuda->foto_url,PATHINFO_BASENAME)]) : '' }}" class="muda-card cursor-pointer border rounded-lg overflow-hidden hover:shadow-lg">
                        @if($minhaMuda->foto_url)
                            @php($fname = pathinfo($minhaMuda->foto_url, PATHINFO_BASENAME))
                            <img src="{{ route('mudas.getFileImage', ['filename' => $fname]) }}"
                                 alt="{{ $minhaMuda->nome }}"
                                 class="w-full h-32 object-cover" />
                        @endif
                        <div class="p-4">
                            <h4 class="font-medium text-gray-800 dark:text-gray-100">{{ $minhaMuda->nome }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $minhaMuda->tipo->nome ?? '' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4 text-right">
                <button type="button" id="close-mudas-modal" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">Cancelar</button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('solicitacao-form');
            const submitButton = document.getElementById('submit-button');
            const statusDiv = document.getElementById('mensagem-status');

            // Se for permuta, controlar habilitação do botão até seleção
            const mudaSelect = document.getElementById('muda_troca_id');
            if (mudaSelect) {
                // Garantir botão desabilitado até seleção
                submitButton.disabled = true;
            }

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Mostrar estado de carregamento
                submitButton.disabled = true;
                submitButton.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Processando...
                `;

                // Limpar mensagens de erro anteriores
                document.querySelectorAll('[id^="erro-"]').forEach(el => {
                    el.textContent = '';
                    el.classList.add('hidden');
                });
                statusDiv.classList.add('hidden');

                // Enviar formulário via AJAX
                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mostrar mensagem de sucesso
                        statusDiv.innerHTML = `
                            <div class="p-4 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 rounded-lg flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    ${data.message}
                                </div>
                            </div>
                        `;
                        statusDiv.classList.remove('hidden');

                        // Disparar evento global para atualizar lista de chats
                        window.dispatchEvent(new Event('solicitacao-criada'));

                        // Redirecionar após alguns segundos
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 2000);
                    } else {
                        // Mostrar mensagem de erro geral
                        if (data.message) {
                            statusDiv.innerHTML = `
                                <div class="p-4 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-lg">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        ${data.message}
                                    </div>
                                </div>
                            `;
                            statusDiv.classList.remove('hidden');
                        }

                        // Mostrar erros específicos por campo
                        if (data.errors) {
                            Object.keys(data.errors).forEach(field => {
                                const errorElement = document.getElementById(`erro-${field}`);
                                if (errorElement) {
                                    errorElement.textContent = data.errors[field][0];
                                    errorElement.classList.remove('hidden');
                                }
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    statusDiv.innerHTML = `
                        <div class="p-4 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-lg">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Ocorreu um erro ao processar sua solicitação. Por favor, tente novamente.
                            </div>
                        </div>
                    `;
                    statusDiv.classList.remove('hidden');
                })
                .finally(() => {
                    // Restaurar o botão
                    submitButton.disabled = false;
                    submitButton.innerHTML = 'Enviar Pedido';
                });
            });

            // Controle do modal de mudas
            const openModalBtn = document.getElementById('open-mudas-modal');
            const closeModalBtn = document.getElementById('close-mudas-modal');
            const modal = document.getElementById('mudas-modal');
            const mudaCards = modal.querySelectorAll('.muda-card');
            const hiddenInput = document.getElementById('muda_troca_id');
            const selectedDisplay = document.getElementById('selected-muda');

            if(openModalBtn) openModalBtn.addEventListener('click', () => modal.classList.remove('hidden'));
            if(closeModalBtn) closeModalBtn.addEventListener('click', () => modal.classList.add('hidden'));

            mudaCards.forEach(card => {
                card.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const name = this.querySelector('h4').innerText;
                    const fotoUrl = this.getAttribute('data-foto');

                    hiddenInput.value = id;
                    // Exibir imagem pequena e nome
                    if (fotoUrl) {
                        selectedDisplay.innerHTML = `<img src="${fotoUrl}" class="inline-block w-8 h-8 rounded-full mr-2" alt="${name}"/> <span>${name}</span>`;
                    } else {
                        selectedDisplay.textContent = 'Selecionado: ' + name;
                    }
                    selectedDisplay.classList.remove('hidden');

                    // Habilitar botão de submit
                    const submitBtn = document.getElementById('submit-button');
                    if(submitBtn) submitBtn.disabled = false;

                    modal.classList.add('hidden');
                });
            });
        });
    </script>
    @endpush

    <script src="https://cdn.jsdelivr.net/npm/preline@1.4.0/dist/preline.min.js"></script>
</x-app-layout>
