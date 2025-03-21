<x-app-layout>
    <div class="py-6 bg-neutral-800/40 dark:bg-neutral-200/40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Seção de Favoritos -->
            <section class="bg-neutral-700/80 dark:bg-neutral-300/80 rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-medium text-white dark:text-black">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            Seus Favoritos
                        </div>
                    </h2>
                    @if(Route::has('mudas.favoritos'))
                        <a href="{{ route('mudas.favoritos') }}" class="text-sm text-emerald-500 hover:text-emerald-600 dark:text-emerald-600 dark:hover:text-emerald-700">
                            Ver todos
                            <span aria-hidden="true"> &rarr;</span>
                        </a>
                    @endif
                </div>

                <!-- Conteúdo dos Favoritos com Skeleton -->
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6" x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 1300)">
                    <template x-if="loading">
                        @for ($i = 0; $i < 4; $i++)
                            <div class="animate-pulse bg-neutral-600/50 dark:bg-neutral-200/50 rounded-xl overflow-hidden">
                                <div class="h-32 bg-neutral-500/50 dark:bg-neutral-400/50"></div>
                                <div class="p-4 space-y-3">
                                    <div class="h-4 bg-neutral-500/50 dark:bg-neutral-400/50 rounded w-3/4"></div>
                                    <div class="h-3 bg-neutral-500/50 dark:bg-neutral-400/50 rounded w-1/2"></div>
                                </div>
                            </div>
                        @endfor
                    </template>

                    <template x-if="!loading">
                        @forelse($favoritos ?? [] as $muda)
                            <div class="group relative bg-neutral-700 dark:bg-neutral-300 border border-neutral-600 dark:border-neutral-400 rounded-xl shadow-sm hover:shadow-lg transition-shadow duration-300">
                                <div class="aspect-w-3 aspect-h-2">
                                    <img class="w-full h-32 object-cover rounded-t-lg" src="{{ $muda->foto_url ?? 'https://images.unsplash.com/photo-1491147334573-44cbb4602074?q=80&w=2574&auto=format&fit=crop' }}" alt="{{ $muda->nome }}">
                                </div>
                                <div class="p-4">
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $muda->nome }}</h3>
                                    <p class="mt-1 text-xs text-emerald-600 dark:text-emerald-400">{{ $muda->tipo->nome ?? 'Desconhecido' }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-8">
                                <p class="text-gray-400 dark:text-neutral-600">Nenhum favorito encontrado</p>
                            </div>
                        @endforelse
                    </template>
                </div>
            </section>

            <!-- Seção Principal - Mudas Disponíveis -->
            <section class="bg-neutral-700/80 dark:bg-neutral-300/80 rounded-xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-white dark:text-black flex items-center mb-6">
                        <svg class="w-5 h-5 mr-2 text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Mudas Disponíveis
                    </h2>

                    <!-- Filtros -->
                    <form action="{{ route('dashboard') }}" method="GET" class="mb-6">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                            <!-- Campos do filtro -->
                            <div>
                                <label for="tipo" class="block text-sm font-medium text-gray-300 dark:text-neutral-700">Tipo</label>
                                <select id="tipo" name="tipo" class="mt-1 block w-full rounded-md border-neutral-600 dark:border-neutral-400 bg-neutral-700 dark:bg-neutral-300 text-white dark:text-black focus:border-emerald-500 focus:ring-emerald-500">
                                    <option value="">Todos</option>
                                    @forelse($tipos ?? [] as $tipo)
                                        <option value="{{ $tipo->id }}" {{ request('tipo') == $tipo->id ? 'selected' : '' }}>
                                            {{ $tipo->nome }}
                                        </option>
                                    @empty
                                        <option value="" disabled>Carregando tipos...</option>
                                    @endforelse
                                </select>
                            </div>

                            <!-- Filtro por Localização -->
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-300 dark:text-neutral-700">Localização</label>
                                <select id="location" name="location" class="mt-1 block w-full rounded-md border-neutral-600 dark:border-neutral-400 bg-neutral-700 dark:bg-neutral-300 text-white dark:text-black focus:border-emerald-500 focus:ring-emerald-500">
                                    <option value="">Todas</option>
                                    @forelse($estados ?? [] as $uf)
                                        <option value="{{ $uf }}" {{ request('location') == $uf ? 'selected' : '' }}>
                                            {{ $uf }}
                                        </option>
                                    @empty
                                        <option value="" disabled>Carregando localizações...</option>
                                    @endforelse
                                </select>
                            </div>

                            <!-- Busca -->
                            <div class="sm:col-span-2">
                                <label for="search" class="block text-sm font-medium text-gray-300 dark:text-neutral-700">Buscar</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="text"
                                        name="search"
                                        id="search"
                                        value="{{ request('search') }}"
                                        class="block w-full rounded-md border-neutral-600 dark:border-neutral-400 bg-neutral-700 dark:bg-neutral-300 text-white dark:text-black pl-10 focus:border-emerald-500 focus:ring-emerald-500"
                                        placeholder="Buscar mudas...">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="mt-4 flex justify-end gap-3">
                            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                                Aplicar Filtros
                            </button>
                        </div>
                    </form>

                    <!-- Grid de Mudas com Skeleton -->
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6" x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 1300)">
                        <template x-if="loading">
                            @for ($i = 0; $i < 6; $i++)
                                <div class="animate-pulse bg-neutral-600/50 dark:bg-neutral-200/50 rounded-xl overflow-hidden">
                                    <div class="h-52 bg-neutral-500/50 dark:bg-neutral-400/50"></div>
                                    <div class="p-6 space-y-4">
                                        <div class="flex justify-between">
                                            <div class="space-y-2">
                                                <div class="h-5 bg-neutral-500/50 dark:bg-neutral-400/50 rounded w-3/4"></div>
                                                <div class="h-4 bg-neutral-500/50 dark:bg-neutral-400/50 rounded w-1/2"></div>
                                            </div>
                                            <div class="h-6 bg-neutral-500/50 dark:bg-neutral-400/50 rounded-full w-20"></div>
                                        </div>
                                        <div class="h-4 bg-neutral-500/50 dark:bg-neutral-400/50 rounded w-full"></div>
                                        <div class="h-4 bg-neutral-500/50 dark:bg-neutral-400/50 rounded w-3/4"></div>
                                        <div class="h-10 bg-neutral-500/50 dark:bg-neutral-400/50 rounded"></div>
                                    </div>
                                </div>
                            @endfor
                        </template>

                        <template x-if="!loading">
                            @forelse($mudas ?? [] as $muda)
                                <div class="group relative bg-neutral-700 dark:bg-neutral-300 border border-neutral-600 dark:border-neutral-400 rounded-xl shadow-sm hover:shadow-lg transition-shadow duration-300">
                                    <div class="aspect-w-3 aspect-h-2">
                                        <img class="w-full h-52 object-cover rounded-t-xl"
                                            src="{{ $muda->foto_url ?? "https://images.unsplash.com/photo-1491147334573-44cbb4602074?q=80&w=2574&auto=format&fit=crop" }}"
                                            alt="{{ $muda->nome }}">
                                    </div>

                                    <div class="p-4 md:p-6">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-300">
                                                    {{ $muda->nome }}
                                                </h3>
                                                <p class="mt-1 text-sm text-emerald-600 dark:text-emerald-400">
                                                    {{ $muda->tipo->nome ?? 'Desconhecido' }}
                                                </p>
                                            </div>
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $muda->status?->nome === 'Disponível' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ $muda->status->nome ?? 'Indisponível' }}
                                            </span>
                                        </div>

                                        <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                                            {{ Str::limit($muda->descricao, 100) }}
                                        </p>

                                        <div class="mt-4 flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                            </svg>
                                            {{ $muda->cidade }}/{{ $muda->uf }}
                                        </div>
                                    </div>

                                    <div class="p-4 border-t border-neutral-600 dark:border-neutral-400">
                                        <a href="{{ route('mudas.show', $muda) }}"
                                            class="w-full inline-flex justify-center items-center gap-2 rounded-lg border border-transparent font-semibold text-emerald-500 hover:text-emerald-700 hover:bg-emerald-100/50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all text-sm py-3 px-4 dark:hover:bg-emerald-800/30 dark:hover:text-emerald-400 dark:focus:ring-offset-gray-800">
                                            Ver detalhes
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full text-center py-8">
                                    <p class="text-gray-400 dark:text-neutral-600">Nenhuma muda encontrada</p>
                                </div>
                            @endforelse
                        </template>
                    </div>
                </div>
            </section>
        </div>
    </div>

    @push('scripts')
    <script>
        // Adiciona tratamento de erro para o form submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Carregando...
            `;
        });

        // Auto-submit do formulário quando selects mudam
        document.querySelectorAll('select[name="tipo"], select[name="location"]').forEach(select => {
            select.addEventListener('change', () => {
                select.closest('form').submit();
            });
        });

        // Debounce para o campo de busca
        let timeoutId;
        const searchInput = document.querySelector('input[name="search"]');

        searchInput.addEventListener('input', () => {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                searchInput.closest('form').submit();
            }, 500);
        });

        // Adiciona recarregamento do skeleton quando filtros são alterados
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                document.querySelectorAll('[x-data]').forEach(el => {
                    if (el.__x) {
                        el.__x.getUnobservedData().loading = true;
                        setTimeout(() => {
                            el.__x.getUnobservedData().loading = false;
                        }, 1300);
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
