<x-app-layout>
    <div class="py-12 bg-neutral-800/40 dark:bg-neutral-200/40">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-neutral-700/80 dark:bg-neutral-300/80 rounded-xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-2xl font-bold text-white dark:text-black flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Nova Muda
                        </h1>
                        <a href="{{ route('dashboard') }}" class="text-emerald-500 hover:text-emerald-600 dark:text-emerald-600 dark:hover:text-emerald-700 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Voltar
                        </a>
                    </div>

                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-lg">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('mudas.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nome da Muda -->
                            <div class="col-span-full">
                                <label for="nome" class="block text-sm font-medium text-gray-300 dark:text-neutral-700 mb-1">
                                    Nome da Muda <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nome" id="nome" value="{{ old('nome') }}"
                                    class="w-full rounded-md border-neutral-600 dark:border-neutral-400 bg-neutral-700 dark:bg-neutral-300 text-white dark:text-black focus:border-emerald-500 focus:ring-emerald-500"
                                    required>
                            </div>

                            <!-- Tipo da Muda -->
                            <div>
                                <label for="tipo_id" class="block text-sm font-medium text-gray-300 dark:text-neutral-700 mb-1">
                                    Tipo <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="tipo_nome" id="tipo_nome"
                                    class="w-full rounded-md border-neutral-600 dark:border-neutral-400 bg-neutral-700 dark:bg-neutral-300 text-white dark:text-black focus:border-emerald-500 focus:ring-emerald-500"
                                    required
                                    placeholder="Ex: Árvore, Arbusto, Flor..."
                                    list="tipos-sugestoes">
                                <datalist id="tipos-sugestoes">
                                    @foreach($tipos as $tipo)
                                        <option value="{{ $tipo->nome }}">
                                    @endforeach
                                </datalist>
                                <input type="hidden" name="tipo_id" id="tipo_id" value="">
                            </div>

                            <!-- Espécie -->
                            <div>
                                <label for="especie_id" class="block text-sm font-medium text-gray-300 dark:text-neutral-700 mb-1">
                                    Espécie <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="especie_nome" id="especie_nome"
                                    class="w-full rounded-md border-neutral-600 dark:border-neutral-400 bg-neutral-700 dark:bg-neutral-300 text-white dark:text-black focus:border-emerald-500 focus:ring-emerald-500"
                                    required
                                    placeholder="Ex: Ipê Amarelo, Pau Brasil, Orquídea..."
                                    list="especies-sugestoes">
                                <datalist id="especies-sugestoes">
                                    @foreach(App\Models\Especie::orderBy('nome')->get() as $especie)
                                        <option value="{{ $especie->nome }}">
                                    @endforeach
                                </datalist>
                                <input type="hidden" name="especie_id" id="especie_id" value="">
                            </div>

                            <!-- Tipo de Solicitação (Doação/Permuta) -->
                            <div>
                                <label for="modo_solicitacao" class="block text-sm font-medium text-gray-300 dark:text-neutral-700 mb-1">
                                    Tipo de Solicitação <span class="text-red-500">*</span>
                                </label>
                                <select name="modo_solicitacao" id="modo_solicitacao"
                                    class="w-full rounded-md border-neutral-600 dark:border-neutral-400 bg-neutral-700 dark:bg-neutral-300 text-white dark:text-black focus:border-emerald-500 focus:ring-emerald-500"
                                    required>
                                    <option value="doacao" {{ old('modo_solicitacao') == 'doacao' ? 'selected' : '' }}>Doação</option>
                                    <option value="permuta" {{ old('modo_solicitacao') == 'permuta' ? 'selected' : '' }}>Permuta</option>
                                </select>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                    <span class="font-medium text-emerald-500">Doação:</span> Você oferece a muda sem esperar nada em troca.<br>
                                    <span class="font-medium text-emerald-500">Permuta:</span> Você troca a muda por outra de seu interesse.
                                </p>
                            </div>

                            <!-- Nota informativa sobre o status -->
                            <div>
                                <div class="flex items-center p-3 bg-emerald-100/20 dark:bg-emerald-100/30 rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm text-emerald-700 dark:text-emerald-800">
                                        Sua muda será cadastrada com status <strong>Disponível</strong>
                                    </span>
                                </div>
                            </div>

                            <!-- Quantidade -->
                            <div>
                                <label for="quantidade" class="block text-sm font-medium text-gray-300 dark:text-neutral-700 mb-1">
                                    Quantidade
                                </label>
                                <input type="number" name="quantidade" id="quantidade" value="{{ old('quantidade') }}" min="1"
                                    class="w-full rounded-md border-neutral-600 dark:border-neutral-400 bg-neutral-700 dark:bg-neutral-300 text-white dark:text-black focus:border-emerald-500 focus:ring-emerald-500">
                            </div>

                            <!-- Descrição -->
                            <div class="col-span-full">
                                <label for="descricao" class="block text-sm font-medium text-gray-300 dark:text-neutral-700 mb-1">
                                    Descrição <span class="text-red-500">*</span>
                                </label>
                                <textarea name="descricao" id="descricao" rows="4"
                                    class="w-full rounded-md border-neutral-600 dark:border-neutral-400 bg-neutral-700 dark:bg-neutral-300 text-white dark:text-black focus:border-emerald-500 focus:ring-emerald-500"
                                    required>{{ old('descricao') }}</textarea>
                            </div>

                            <!-- Foto com preview simplificado -->
                            <div class="col-span-full">
                                <label class="block text-sm font-medium text-gray-300 dark:text-neutral-700 mb-2">
                                    Foto da Muda (Opcional)
                                </label>

                                <!-- Container principal -->
                                <div class="space-y-4">
                                    <!-- Área de preview da imagem -->
                                    <div id="imagePreviewArea" class="w-full h-64 rounded-xl overflow-hidden border-2 border-emerald-500/50 relative flex items-center justify-center">
                                        <!-- Estado vazio (padrão) -->
                                        <div id="emptyState" class="flex flex-col items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-emerald-500/60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="mt-4 text-emerald-500/80 text-center px-4 font-medium">Nenhuma imagem selecionada</p>
                                        </div>

                                        <!-- Preview da imagem -->
                                        <img id="imagePreview" class="absolute inset-0 w-full h-full object-cover hidden" alt="Preview da imagem">

                                        <!-- Status de upload (aparece sobre a imagem) -->
                                        <div id="uploadStatus" class="absolute inset-0 flex items-center justify-center hidden">
                                            <div class="bg-black/75 text-white p-4 rounded-lg flex items-center">
                                                <svg id="loadingIcon" class="animate-spin h-6 w-6 mr-2 text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                <span id="statusText">Carregando imagem...</span>
                                            </div>
                                        </div>

                                        <!-- Botão de remover (aparece apenas quando tem imagem) -->
                                        <button type="button" id="removeImage" class="absolute top-3 right-3 bg-red-500 text-white rounded-full p-2 shadow-lg hover:bg-red-600 transition-all hidden">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Botão de upload estilizado -->
                                    <label for="foto" class="cursor-pointer block">
                                        <div class="flex items-center justify-center w-full bg-emerald-600 hover:bg-emerald-700 text-white py-3 px-4 rounded-lg transition-all shadow-md">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                            <span>Selecionar imagem</span>
                                        </div>
                                        <input type="file" id="foto" name="foto" accept="image/*" class="hidden">
                                    </label>

                                    <!-- Informações sobre formatos aceitos -->
                                    <div class="flex items-center p-3 bg-gray-700/20 dark:bg-gray-300/20 rounded-lg text-sm text-gray-400 dark:text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Formatos aceitos: JPG, PNG, GIF - Tamanho máximo: 2MB</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-full">
                                <h3 class="text-lg font-medium text-white dark:text-black mb-4">Localização</h3>

                                <!-- Botões de preenchimento rápido -->
                                <div class="flex flex-wrap gap-2 mb-4">
                                    <button type="button" id="btnMeuEndereco"
                                        class="px-3 py-1.5 text-xs rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Meu Endereço
                                    </button>
                                    <button type="button" id="btnUltimoEndereco"
                                        class="px-3 py-1.5 text-xs rounded-lg bg-blue-600 hover:bg-blue-700 text-white flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Último Endereço Usado
                                    </button>
                                    <div class="flex items-center ml-auto">
                                        <input type="checkbox" id="setAsDefault" name="setAsDefault" value="1" class="rounded border-gray-400 text-emerald-600 focus:ring-emerald-500">
                                        <label for="setAsDefault" class="ml-2 text-sm text-gray-300 dark:text-neutral-700">
                                            Salvar como endereço padrão
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- CEP -->
                            <div>
                                <label for="cep" class="block text-sm font-medium text-gray-300 dark:text-neutral-700 mb-1">
                                    CEP <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="cep" id="cep" value="{{ old('cep') }}"
                                    class="w-full rounded-md border-neutral-600 dark:border-neutral-400 bg-neutral-700 dark:bg-neutral-300 text-white dark:text-black focus:border-emerald-500 focus:ring-emerald-500"
                                    required maxlength="8" placeholder="00000000">
                            </div>

                            <!-- Logradouro -->
                            <div class="col-span-full md:col-span-1">
                                <label for="logradouro" class="block text-sm font-medium text-gray-300 dark:text-neutral-700 mb-1">
                                    Logradouro <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="logradouro" id="logradouro" value="{{ old('logradouro') }}"
                                    class="w-full rounded-md border-neutral-600 dark:border-neutral-400 bg-neutral-700 dark:bg-neutral-300 text-white dark:text-black focus:border-emerald-500 focus:ring-emerald-500"
                                    required>
                            </div>

                            <!-- Número -->
                            <div>
                                <label for="numero" class="block text-sm font-medium text-gray-300 dark:text-neutral-700 mb-1">
                                    Número <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="numero" id="numero" value="{{ old('numero') }}"
                                    class="w-full rounded-md border-neutral-600 dark:border-neutral-400 bg-neutral-700 dark:bg-neutral-300 text-white dark:text-black focus:border-emerald-500 focus:ring-emerald-500"
                                    required>
                            </div>

                            <!-- Complemento -->
                            <div>
                                <label for="complemento" class="block text-sm font-medium text-gray-300 dark:text-neutral-700 mb-1">
                                    Complemento
                                </label>
                                <input type="text" name="complemento" id="complemento" value="{{ old('complemento') }}"
                                    class="w-full rounded-md border-neutral-600 dark:border-neutral-400 bg-neutral-700 dark:bg-neutral-300 text-white dark:text-black focus:border-emerald-500 focus:ring-emerald-500">
                            </div>

                            <!-- Bairro -->
                            <div>
                                <label for="bairro" class="block text-sm font-medium text-gray-300 dark:text-neutral-700 mb-1">
                                    Bairro <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="bairro" id="bairro" value="{{ old('bairro') }}"
                                    class="w-full rounded-md border-neutral-600 dark:border-neutral-400 bg-neutral-700 dark:bg-neutral-300 text-white dark:text-black focus:border-emerald-500 focus:ring-emerald-500"
                                    required>
                            </div>

                            <!-- Cidade -->
                            <div>
                                <label for="cidade" class="block text-sm font-medium text-gray-300 dark:text-neutral-700 mb-1">
                                    Cidade <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="cidade" id="cidade" value="{{ old('cidade') }}"
                                    class="w-full rounded-md border-neutral-600 dark:border-neutral-400 bg-neutral-700 dark:bg-neutral-300 text-white dark:text-black focus:border-emerald-500 focus:ring-emerald-500"
                                    required>
                            </div>

                            <!-- UF -->
                            <div>
                                <label for="uf" class="block text-sm font-medium text-gray-300 dark:text-neutral-700 mb-1">
                                    UF <span class="text-red-500">*</span>
                                </label>
                                <select name="uf" id="uf"
                                    class="w-full rounded-md border-neutral-600 dark:border-neutral-400 bg-neutral-700 dark:bg-neutral-300 text-white dark:text-black focus:border-emerald-500 focus:ring-emerald-500"
                                    required>
                                    <option value="">Selecione um estado</option>
                                    @foreach(['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'] as $uf)
                                        <option value="{{ $uf }}" {{ old('uf') == $uf ? 'selected' : '' }}>{{ $uf }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="flex justify-end space-x-3 pt-4">
                            <a href="{{ route('dashboard') }}"
                                class="px-6 py-2 border border-neutral-600 dark:border-neutral-400 text-neutral-300 dark:text-neutral-700 rounded-lg hover:bg-neutral-600 hover:text-white dark:hover:bg-neutral-400 dark:hover:text-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-500 transition-colors">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="px-6 py-2 bg-gradient-to-r from-emerald-500 to-emerald-700 text-white font-medium rounded-lg shadow-lg hover:from-emerald-600 hover:to-emerald-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-300">
                                Cadastrar Muda
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            console.log("DOM carregado, iniciando script de upload");

            const imageInput = document.getElementById("foto");
            const imagePreview = document.getElementById("imagePreview");
            const emptyState = document.getElementById("emptyState");
            const removeImageButton = document.getElementById("removeImage");
            const uploadStatus = document.getElementById("uploadStatus");
            const statusText = document.getElementById("statusText");

            console.log("Elementos encontrados:", {
                imageInput: !!imageInput,
                imagePreview: !!imagePreview,
                emptyState: !!emptyState,
                removeImageButton: !!removeImageButton,
                uploadStatus: !!uploadStatus
            });

            // Função para mostrar o status de upload
            function showStatus(message, isLoading = true) {
                statusText.textContent = message;
                uploadStatus.classList.remove("hidden");

                document.getElementById("loadingIcon").style.display = isLoading ? "inline-block" : "none";

                console.log("Status exibido:", message, "isLoading:", isLoading);

                if (!isLoading) {
                    setTimeout(() => {
                        uploadStatus.classList.add("hidden");
                        console.log("Status ocultado após timeout");
                    }, 3000);
                }
            }

            // Função para mostrar a visualização da imagem
            function showPreview(fileUrl) {
                imagePreview.src = fileUrl;
                imagePreview.classList.remove("hidden");
                emptyState.classList.add("hidden");
                removeImageButton.classList.remove("hidden");
                console.log("Preview de imagem exibido");
            }

            // Função para mostrar o estado vazio
            function showEmptyState() {
                imagePreview.classList.add("hidden");
                emptyState.classList.remove("hidden");
                removeImageButton.classList.add("hidden");
                uploadStatus.classList.add("hidden");
                console.log("Estado vazio exibido");
            }

            // Inicializar com o estado vazio
            showEmptyState();
            console.log("Estado inicial configurado");

            if (imageInput) {
                imageInput.addEventListener("change", function(event) {
                    console.log("Evento change do input de arquivo disparado");
                    const file = event.target.files[0];

                    if (!file) {
                        console.log("Nenhum arquivo selecionado");
                        showEmptyState();
                        return;
                    }

                    console.log("Arquivo selecionado:", file.name, "Tipo:", file.type, "Tamanho:", file.size);

                    showStatus("Carregando imagem...");

                    // Validar tamanho
                    if (file.size > 2 * 1024 * 1024) {
                        console.log("Arquivo muito grande");
                        imageInput.value = "";
                        showStatus("Arquivo muito grande! Máximo: 2MB", false);
                        return;
                    }

                    // Validar formato
                    if (!["image/jpeg", "image/png", "image/gif"].includes(file.type)) {
                        console.log("Formato não suportado");
                        imageInput.value = "";
                        showStatus("Formato não suportado! Use JPG, PNG ou GIF", false);
                        return;
                    }

                    // Criar URL para o arquivo
                    const fileUrl = URL.createObjectURL(file);
                    console.log("URL do arquivo criada:", fileUrl);

                    const img = new Image();
                    img.onload = function() {
                        console.log("Imagem carregada com sucesso, dimensões:", img.width, "x", img.height);

                        showPreview(fileUrl);

                        showStatus("Imagem carregada com sucesso!", false);
                    };

                    img.onerror = function() {
                        console.error("Erro ao carregar imagem");
                        imageInput.value = "";
                        showStatus("Erro ao carregar imagem!", false);
                    };

                    console.log("Iniciando carregamento da imagem");
                    img.src = fileUrl;
                });

                // Botão para remover a imagem
                if (removeImageButton) {
                    removeImageButton.addEventListener("click", function(e) {
                        console.log("Botão remover clicado");
                        e.preventDefault();
                        imageInput.value = "";
                        showEmptyState();
                    });
                }
            }

            console.log("Script de upload inicializado com sucesso");

            // Consulta de CEP e preenchimento automático de endereço
            const cepInput = document.getElementById('cep');
            if (cepInput) {
                cepInput.addEventListener('blur', buscarCep);
                cepInput.addEventListener('input', function() {
                    // Remover todos os caracteres não numéricos
                    this.value = this.value.replace(/\D/g, '');
                    if (this.value.length === 8) {
                        buscarCep();
                    }
                });

                function buscarCep() {
                    const cep = cepInput.value.trim().replace(/\D/g, '');

                    if (cep.length !== 8) {
                        return;
                    }

                    // Mostrar indicador de carregamento no campo CEP
                    cepInput.classList.add('opacity-50');

                    // Adicionar mensagem de carregamento temporária
                    const loadingMsg = document.createElement('div');
                    loadingMsg.id = 'cep-loading';
                    loadingMsg.className = 'text-xs text-emerald-500 mt-1 flex items-center';
                    loadingMsg.innerHTML = `
                        <svg class="animate-spin h-3 w-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Consultando CEP...</span>
                    `;

                    const existingMsg = document.getElementById('cep-loading');
                    if (existingMsg) {
                        existingMsg.remove();
                    }

                    cepInput.parentElement.appendChild(loadingMsg);

                    // Consultar a API do ViaCEP
                    fetch(`https://viacep.com.br/ws/${cep}/json/`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Erro na consulta do CEP');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.erro) {
                                throw new Error('CEP não encontrado');
                            }

                            // Preencher os campos com os dados retornados
                            document.getElementById('logradouro').value = data.logradouro || '';
                            document.getElementById('bairro').value = data.bairro || '';
                            document.getElementById('cidade').value = data.localidade || '';

                            const ufSelect = document.getElementById('uf');
                            if (ufSelect) {
                                for (let i = 0; i < ufSelect.options.length; i++) {
                                    if (ufSelect.options[i].value === data.uf) {
                                        ufSelect.selectedIndex = i;
                                        break;
                                    }
                                }
                            }

                            // Focar no campo número após preenchimento
                            document.getElementById('numero').focus();

                            // Mostrar mensagem de sucesso temporária
                            const successMsg = document.createElement('div');
                            successMsg.className = 'text-xs text-emerald-500 mt-1';
                            successMsg.textContent = 'Endereço preenchido com sucesso!';

                            if (loadingMsg) {
                                loadingMsg.remove();
                            }

                            cepInput.parentElement.appendChild(successMsg);
                            setTimeout(() => successMsg.remove(), 3000);
                        })
                        .catch(error => {
                            console.error('Erro ao consultar CEP:', error);

                            // Mostrar mensagem de erro temporária
                            const errorMsg = document.createElement('div');
                            errorMsg.className = 'text-xs text-red-500 mt-1';
                            errorMsg.textContent = error.message === 'CEP não encontrado'
                                ? 'CEP não encontrado'
                                : 'Erro ao consultar o CEP';

                            if (loadingMsg) {
                                loadingMsg.remove();
                            }

                            cepInput.parentElement.appendChild(errorMsg);
                            setTimeout(() => errorMsg.remove(), 3000);
                        })
                        .finally(() => {
                            cepInput.classList.remove('opacity-50');
                        });
                }
            }

            // Funcionalidades para botões de endereço
            const btnMeuEndereco = document.getElementById('btnMeuEndereco');
            const btnUltimoEndereco = document.getElementById('btnUltimoEndereco');
            const setAsDefault = document.getElementById('setAsDefault');

            // Manipulador de evento para botão "Meu Endereço"
            if (btnMeuEndereco) {
                btnMeuEndereco.addEventListener('click', function() {
                    // Buscar endereço do usuário via AJAX
                    fetch('/user/address')
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Erro ao buscar o endereço');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // Preencher os campos de endereço
                                document.getElementById('cep').value = data.address.cep || '';
                                document.getElementById('logradouro').value = data.address.logradouro || '';
                                document.getElementById('numero').value = data.address.numero || '';
                                document.getElementById('complemento').value = data.address.complemento || '';
                                document.getElementById('bairro').value = data.address.bairro || '';
                                document.getElementById('cidade').value = data.address.cidade || '';

                                // Selecionar a UF no dropdown
                                const ufSelect = document.getElementById('uf');
                                if (ufSelect && data.address.uf) {
                                    for (let i = 0; i < ufSelect.options.length; i++) {
                                        if (ufSelect.options[i].value === data.address.uf) {
                                            ufSelect.selectedIndex = i;
                                            break;
                                        }
                                    }
                                }

                                // Mostrar mensagem de sucesso temporária
                                showAddressMessage('Endereço carregado com sucesso!', 'emerald');

                                // Focar no campo número após preenchimento
                                document.getElementById('numero').focus();
                            } else {
                                throw new Error(data.message || 'Endereço não encontrado');
                            }
                        })
                        .catch(error => {
                            console.error('Erro:', error);
                            showAddressMessage(error.message || 'Erro ao carregar o endereço', 'red');
                        });
                });
            }

            // Manipulador de evento para botão "Último Endereço Usado"
            if (btnUltimoEndereco) {
                btnUltimoEndereco.addEventListener('click', function() {
                    // Buscar último endereço usado via AJAX
                    fetch('/user/last-address')
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Erro ao buscar o último endereço');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // Preencher os campos de endereço
                                document.getElementById('cep').value = data.address.cep || '';
                                document.getElementById('logradouro').value = data.address.logradouro || '';
                                document.getElementById('numero').value = data.address.numero || '';
                                document.getElementById('complemento').value = data.address.complemento || '';
                                document.getElementById('bairro').value = data.address.bairro || '';
                                document.getElementById('cidade').value = data.address.cidade || '';

                                // Selecionar a UF no dropdown
                                const ufSelect = document.getElementById('uf');
                                if (ufSelect && data.address.uf) {
                                    for (let i = 0; i < ufSelect.options.length; i++) {
                                        if (ufSelect.options[i].value === data.address.uf) {
                                            ufSelect.selectedIndex = i;
                                            break;
                                        }
                                    }
                                }

                                // Mostrar mensagem de sucesso temporária
                                showAddressMessage('Último endereço carregado com sucesso!', 'emerald');

                                // Focar no campo número após preenchimento
                                document.getElementById('numero').focus();
                            } else {
                                throw new Error(data.message || 'Nenhum endereço anterior encontrado');
                            }
                        })
                        .catch(error => {
                            console.error('Erro:', error);
                            showAddressMessage(error.message || 'Erro ao carregar o último endereço', 'red');
                        });
                });
            }

            // Função para mostrar mensagens de status do endereço
            function showAddressMessage(message, type = 'emerald') {
                const addressSection = document.querySelector('h3:contains("Localização")').parentElement;

                // Remover mensagens anteriores
                const existingMsg = document.getElementById('address-message');
                if (existingMsg) {
                    existingMsg.remove();
                }

                // Criar nova mensagem
                const messageElement = document.createElement('div');
                messageElement.id = 'address-message';
                messageElement.className = `text-sm text-${type}-500 mt-2 mb-3 flex items-center`;

                if (type === 'emerald') {
                    messageElement.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    `;
                } else {
                    messageElement.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    `;
                }

                messageElement.innerHTML += `<span>${message}</span>`;

                // Inserir após o título da seção
                const buttonsContainer = document.querySelector('.flex.flex-wrap.gap-2.mb-4');
                buttonsContainer.parentNode.insertBefore(messageElement, buttonsContainer.nextSibling);

                // Remover após 3 segundos
                setTimeout(() => {
                    messageElement.remove();
                }, 3000);
            }

            // Correção para o seletor querySelector
            document.querySelector = document.querySelector || function(selector) {
                if (selector.startsWith(':contains(')) {
                    const text = selector.match(/:contains\(['"](.+)['"]\)/)[1];
                    const elements = document.getElementsByTagName('*');

                    for (let i = 0; i < elements.length; i++) {
                        if (elements[i].textContent.includes(text)) {
                            return elements[i];
                        }
                    }
                    return null;
                }

                return document.querySelector(selector);
            };

            // Configuração para campos de tipo e espécie
            const tipoNomeInput = document.getElementById('tipo_nome');
            const tipoIdInput = document.getElementById('tipo_id');
            const especieNomeInput = document.getElementById('especie_nome');
            const especieIdInput = document.getElementById('especie_id');

            // Manipular o envio do formulário para garantir que os campos tipo_id e especie_id sejam enviados
            document.querySelector('form').addEventListener('submit', function(event) {
                // Tipo e espécie são sempre válidos, mesmo que sejam novos
                // O controlador vai criar novos registros se necessário

                console.log('Formulário sendo enviado');
                console.log('Tipo nome:', tipoNomeInput.value);
                console.log('Espécie nome:', especieNomeInput.value);
            });
        });
    </script>
    @endpush
</x-app-layout>
