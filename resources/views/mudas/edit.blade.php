<x-app-layout>
    <div class="py-12 bg-neutral-800/40 dark:bg-neutral-200/40">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-neutral-700/80 dark:bg-neutral-300/80 rounded-xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-2xl font-bold text-white dark:text-black flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Editar Muda
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

                    <form action="{{ route('mudas.update', $muda) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nome da Muda -->
                            <div class="col-span-full">
                                <label for="nome" class="block text-sm font-medium text-white dark:text-neutral-700 mb-1">
                                    Nome da Muda <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nome" id="nome" value="{{ old('nome', $muda->nome) }}"
                                    class="w-full rounded-md border-neutral-600 dark:border-neutral-400 bg-neutral-700 dark:bg-neutral-300 text-white dark:text-black focus:border-emerald-500 focus:ring-emerald-500"
                                    required>
                            </div>
                            <!-- Tipo da Muda -->
                            <div>
                                <label for="tipo_id" class="block text-sm font-medium text-white dark:text-neutral-700 mb-1">
                                    Tipo <span class="text-red-500">*</span>
                                </label>
                                <select name="tipo_id" id="tipo_id"
                                    class="w-full rounded-md border-neutral-600 dark:border-neutral-400 bg-neutral-700 dark:bg-neutral-300 text-white dark:text-black focus:border-emerald-500 focus:ring-emerald-500"
                                    required>
                                    <option value="">Selecione um tipo</option>
                                    @foreach($tipos as $tipo)
                                        <option value="{{ $tipo->id }}" {{ old('tipo_id', $muda->tipos_id) == $tipo->id ? 'selected' : '' }}>
                                            {{ $tipo->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Espécie -->
                            <div>
                                <label for="especie_id" class="block text-sm font-medium text-white dark:text-neutral-700 mb-1">
                                    Espécie <span class="text-red-500">*</span>
                                </label>
                                <select name="especie_id" id="especie_id"
                                    class="w-full rounded-md border-neutral-600 dark:border-neutral-400 bg-neutral-700 dark:bg-neutral-300 text-white dark:text-black focus:border-emerald-500 focus:ring-emerald-500"
                                    required>
                                    <option value="">Selecione uma espécie</option>
                                    @foreach(App\Models\Especie::orderBy('nome')->get() as $especie)
                                        <option value="{{ $especie->id }}" {{ old('especie_id', $muda->especie_id) == $especie->id ? 'selected' : '' }}>
                                            {{ $especie->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Status -->
                            <div>
                                <label for="status_id" class="block text-sm font-medium text-white dark:text-neutral-700 mb-1">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select name="status_id" id="status_id"
                                    class="w-full rounded-md border-neutral-600 dark:border-neutral-400 bg-neutral-700 dark:bg-neutral-300 text-white dark:text-black focus:border-emerald-500 focus:ring-emerald-500"
                                    required>
                                    <option value="">Selecione um status</option>
                                    @foreach($status as $st)
                                        <option value="{{ $st->id }}" {{ old('status_id', $muda->muda_status_id) == $st->id ? 'selected' : '' }}>
                                            {{ $st->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Quantidade -->
                            <div>
                                <label for="quantidade" class="block text-sm font-medium text-white dark:text-neutral-700 mb-1">
                                    Quantidade
                                </label>
                                <input type="number" name="quantidade" id="quantidade" value="{{ old('quantidade', $muda->quantidade) }}" min="1"
                                    class="w-full rounded-md border-neutral-600 dark:border-neutral-400 bg-neutral-700 dark:bg-neutral-300 text-white dark:text-black focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                            <!-- Descrição -->
                            <div class="col-span-full">
                                <label for="descricao" class="block text-sm font-medium text-white dark:text-neutral-700 mb-1">
                                    Descrição <span class="text-red-500">*</span>
                                </label>
                                <textarea name="descricao" id="descricao" rows="4"
                                    class="w-full rounded-md border-neutral-600 dark:border-neutral-400 bg-neutral-700 dark:bg-neutral-300 text-white dark:text-black focus:border-emerald-500 focus:ring-emerald-500"
                                    required>{{ old('descricao', $muda->descricao) }}</textarea>
                            </div>
                            <!-- Foto -->
                            <div class="col-span-full">
                                <label class="block text-sm font-medium text-gray-300 dark:text-neutral-700 mb-2">
                                    Foto da Muda (Opcional)
                                </label>
                                <div class="space-y-4">
                                    <!-- Área de preview da imagem -->
                                    <div id="imagePreviewArea" class="w-full h-64 rounded-xl overflow-hidden border-2 border-emerald-500/50 relative flex items-center justify-center mb-2">
                                        <!-- Estado vazio (padrão) -->
                                        <div id="emptyState" class="flex flex-col items-center justify-center {{ $muda->foto_url ? 'hidden' : '' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-emerald-500/60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="mt-4 text-emerald-500/80 text-center px-4 font-medium">Nenhuma imagem selecionada</p>
                                        </div>
                                        <!-- Preview da imagem -->
                                        @if($muda->foto_url)
                                            @php
                                                $filename = pathinfo($muda->foto_url, PATHINFO_BASENAME);
                                            @endphp
                                            <img id="imagePreview" class="absolute inset-0 w-full h-full object-cover" alt="Preview da imagem" src="{{ route('mudas.getFileImage', ['filename' => $filename]) }}">
                                        @else
                                            <img id="imagePreview" class="absolute inset-0 w-full h-full object-cover hidden" alt="Preview da imagem" src="">
                                        @endif
                                        <!-- Status de upload (aparece sobre a imagem) -->
                                        <div id="uploadStatus" class="absolute inset-0 flex items-center justify-center hidden">
                                            <div class="bg-black/75 text-white p-4 rounded-lg flex items-center">
                                                <svg id="loadingIcon" class="animate-spin h-6 w-6 mr-2 text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                <span id="statusText">Carregando imagem...</span>
                                            </div>
                                        </div>
                                        <!-- Botão de remover (aparece apenas quando tem imagem) -->
                                        <button type="button" id="removeImage" class="absolute top-3 right-3 bg-red-500 text-white rounded-full p-2 shadow-lg hover:bg-red-600 transition-all {{ $muda->foto_url ? '' : 'hidden' }}">
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Formatos aceitos: JPG, PNG, GIF - Tamanho máximo: 2MB</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-full">
                                <h3 class="text-lg font-medium text-white dark:text-black mb-4">Localização</h3>
                            </div>
                            <!-- CEP -->
                            <div>
                                <label for="cep" class="block text-sm font-medium text-white dark:text-neutral-700 mb-1">
                                    CEP <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="cep" id="cep" value="{{ old('cep', $muda->cep) }}"
                                    class="w-full rounded-md border-neutral-600 dark:border-neutral-400 bg-neutral-700 dark:bg-neutral-300 text-white dark:text-black focus:border-emerald-500 focus:ring-emerald-500"
                                    required maxlength="8" placeholder="00000000">
                            </div>
                            <!-- Logradouro -->
                            <div class="col-span-full md:col-span-1">
                                <label for="logradouro" class="block text-sm font-medium text-white dark:text-neutral-700 mb-1">
                                    Logradouro <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="logradouro" id="logradouro" value="{{ old('logradouro', $muda->logradouro) }}"
                                    class="w-full rounded-md border-neutral-600 dark:border-neutral-400 bg-neutral-700 dark:bg-neutral-300 text-white dark:text-black focus:border-emerald-500 focus:ring-emerald-500"
                                    required>
                            </div>
                            <!-- Número -->
                            <div>
                                <label for="numero" class="block text-sm font-medium text-white dark:text-neutral-700 mb-1">
                                    Número <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="numero" id="numero" value="{{ old('numero', $muda->numero) }}"
                                    class="w-full rounded-md border-neutral-600 dark:border-neutral-400 bg-neutral-700 dark:bg-neutral-300 text-white dark:text-black focus:border-emerald-500 focus:ring-emerald-500"
                                    required>
                            </div>
                            <!-- Complemento -->
                            <div>
                                <label for="complemento" class="block text-sm font-medium text-white dark:text-neutral-700 mb-1">
                                    Complemento
                                </label>
                                <input type="text" name="complemento" id="complemento" value="{{ old('complemento', $muda->complemento) }}"
                                    class="w-full rounded-md border-neutral-600 dark:border-neutral-400 bg-neutral-700 dark:bg-neutral-300 text-white dark:text-black focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                            <!-- Bairro -->
                            <div>
                                <label for="bairro" class="block text-sm font-medium text-white dark:text-neutral-700 mb-1">
                                    Bairro <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="bairro" id="bairro" value="{{ old('bairro', $muda->bairro) }}"
                                    class="w-full rounded-md border-neutral-600 dark:border-neutral-400 bg-neutral-700 dark:bg-neutral-300 text-white dark:text-black focus:border-emerald-500 focus:ring-emerald-500"
                                    required>
                            </div>
                            <!-- Cidade -->
                            <div>
                                <label for="cidade" class="block text-sm font-medium text-white dark:text-neutral-700 mb-1">
                                    Cidade <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="cidade" id="cidade" value="{{ old('cidade', $muda->cidade) }}"
                                    class="w-full rounded-md border-neutral-600 dark:border-neutral-400 bg-neutral-700 dark:bg-neutral-300 text-white dark:text-black focus:border-emerald-500 focus:ring-emerald-500"
                                    required>
                            </div>
                            <!-- UF -->
                            <div>
                                <label for="uf" class="block text-sm font-medium text-white dark:text-neutral-700 mb-1">
                                    UF <span class="text-red-500">*</span>
                                </label>
                                <select name="uf" id="uf"
                                    class="w-full rounded-md border-neutral-600 dark:border-neutral-400 bg-neutral-700 dark:bg-neutral-300 text-white dark:text-black focus:border-emerald-500 focus:ring-emerald-500"
                                    required>
                                    <option value="">Selecione um estado</option>
                                    @foreach(['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'] as $uf)
                                        <option value="{{ $uf }}" {{ old('uf', $muda->uf) == $uf ? 'selected' : '' }}>{{ $uf }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Botões -->
                        <div class="flex justify-end space-x-3 pt-4">
                            <a href="{{ route('dashboard') }}"
                                class="px-6 py-2 border border-neutral-600 dark:border-neutral-400 text-white dark:text-neutral-700 rounded-lg hover:bg-neutral-600 hover:text-white dark:hover:bg-neutral-400 dark:hover:text-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-500 transition-colors">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="px-6 py-2 bg-gradient-to-r from-yellow-500 to-yellow-700 text-white font-medium rounded-lg shadow-lg hover:from-yellow-600 hover:to-yellow-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all duration-300">
                                Salvar Alterações
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
            const imageInput = document.getElementById("foto");
            const imagePreview = document.getElementById("imagePreview");
            const emptyState = document.getElementById("emptyState");
            const removeImageButton = document.getElementById("removeImage");
            const uploadStatus = document.getElementById("uploadStatus");
            const statusText = document.getElementById("statusText");

            function showStatus(message, isLoading = true) {
                statusText.textContent = message;
                uploadStatus.classList.remove("hidden");
                document.getElementById("loadingIcon").style.display = isLoading ? "inline-block" : "none";
                if (!isLoading) {
                    setTimeout(() => {
                        uploadStatus.classList.add("hidden");
                    }, 3000);
                }
            }
            function showPreview(fileUrl) {
                imagePreview.src = fileUrl;
                imagePreview.classList.remove("hidden");
                emptyState.classList.add("hidden");
                removeImageButton.classList.remove("hidden");
            }
            function showEmptyState() {
                imagePreview.classList.add("hidden");
                emptyState.classList.remove("hidden");
                removeImageButton.classList.add("hidden");
                uploadStatus.classList.add("hidden");
            }
            // Inicializar preview com imagem existente, se houver
            if (imagePreview.src && imagePreview.src !== window.location.href) {
                showPreview(imagePreview.src);
            } else {
                showEmptyState();
            }
            if (imageInput) {
                imageInput.addEventListener("change", function(event) {
                    const file = event.target.files[0];
                    if (!file) {
                        showEmptyState();
                        return;
                    }
                    showStatus("Carregando imagem...");
                    if (file.size > 2 * 1024 * 1024) {
                        imageInput.value = "";
                        showStatus("Arquivo muito grande! Máximo: 2MB", false);
                        return;
                    }
                    if (!["image/jpeg", "image/png", "image/gif"].includes(file.type)) {
                        imageInput.value = "";
                        showStatus("Formato não suportado! Use JPG, PNG ou GIF", false);
                        return;
                    }
                    const fileUrl = URL.createObjectURL(file);
                    const img = new Image();
                    img.onload = function() {
                        showPreview(fileUrl);
                        showStatus("Imagem carregada com sucesso!", false);
                    };
                    img.onerror = function() {
                        imageInput.value = "";
                        showStatus("Erro ao carregar imagem!", false);
                    };
                    img.src = fileUrl;
                });
                if (removeImageButton) {
                    removeImageButton.addEventListener("click", function(e) {
                        e.preventDefault();
                        imageInput.value = "";
                        showEmptyState();
                    });
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
