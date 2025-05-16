<x-app-layout>
    @php
        $unsplashImages = [
            'https://images.unsplash.com/photo-1491147334573-44cbb4602074?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?auto=format&fit=crop&w=700&q=80',
            'https://images.unsplash.com/photo-1520412099551-62b6bafeb5bb?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1509223197845-458d87318791?auto=format&fit=crop&w=750&q=80',
            'https://images.unsplash.com/photo-1530968464165-7a1861cbaf9f?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1459156212016-c812468e2115?auto=format&fit=crop&w=650&q=80',
        ];
    @endphp

    <div class="py-6 bg-neutral-800/40 dark:bg-neutral-200/40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="flex justify-end">
                <a href="{{ route('mudas.create') }}"
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-500 to-emerald-700 text-white font-medium rounded-lg shadow-lg hover:from-emerald-600 hover:to-emerald-800 focus:outline-none focus:ring-4 focus:ring-emerald-500/50 transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Publicar Nova Muda
                </a>
            </div>

            <!-- Seção de Favoritos -->
            <section class="bg-neutral-700/80 dark:bg-neutral-300/80 rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-medium text-white dark:text-black">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            Seus Favoritos
                        </div>
                    </h2>
                    @if(Route::has('mudas.favoritos'))
                        <a href="{{ route('mudas.favoritos') }}"
                        class="text-sm text-emerald-500 hover:text-emerald-600 dark:text-emerald-600 dark:hover:text-emerald-700">
                            Ver todos
                            <span aria-hidden="true"> &rarr;</span>
                        </a>
                    @endif
                </div>
                <div id="favoritos-list" class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @include('dashboard.partials.favoritos', ['favoritos' => $favoritos])
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

                    @if(isset($error))
                        <div class="mb-6 p-4 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300">
                            {{ $error }}
                        </div>
                    @endif

                    <!-- Filtros -->
                        <!-- Formulário -->
<form action="{{ route('dashboard') }}" method="GET" class="mb-6">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Filtro Tipo -->
        <div>
            <label for="tipo" class="block text-sm font-medium text-gray-300 dark:text-neutral-700">Tipo</label>
            <select id="tipo" name="tipo"
                    class="mt-1 block w-full rounded-md border-neutral-600 dark:border-neutral-400 bg-neutral-700 dark:bg-neutral-300 text-white dark:text-black focus:border-emerald-500 focus:ring-emerald-500">
                <option value="">Todos</option>
                @forelse($tipos ?? [] as $tipo)
                    <option value="{{ $tipo->id }}" {{ request()->get('tipo') == $tipo->id ? 'selected' : '' }}>
                        {{ $tipo->nome }}
                    </option>
                @empty
                    <option value="" disabled>Carregando tipos...</option>
                @endforelse
            </select>
        </div>

        <!-- Filtro Localização -->
        <div>
            <label for="location" class="block text-sm font-medium text-gray-300 dark:text-neutral-700">Localização</label>
            <select id="location" name="location"
                    class="mt-1 block w-full rounded-md border-neutral-600 dark:border-neutral-400 bg-neutral-700 dark:bg-neutral-300 text-white dark:text-black focus:border-emerald-500 focus:ring-emerald-500">
                <option value="">Todas</option>
                @forelse($estados ?? [] as $uf)
                    <option value="{{ $uf }}" {{ request()->get('location') == $uf ? 'selected' : '' }}>
                        {{ $uf }}
                    </option>
                @empty
                    <option value="" disabled>Carregando localizações...</option>
                @endforelse
            </select>
        </div>

        <!-- Campo de busca -->
        <div class="sm:col-span-2">
            <label for="search" class="block text-sm font-medium text-gray-300 dark:text-neutral-700">Buscar</label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <input type="text"
                       name="search"
                       id="search"
                       value="{{ is_array(request('search')) ? request('search')[0] : request('search') }}"
                       class="block w-full rounded-md border-neutral-600 dark:border-neutral-400 bg-neutral-700 dark:bg-neutral-300 text-white dark:text-black pl-10 focus:border-emerald-500 focus:ring-emerald-500"
                       placeholder="Buscar mudas...">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                              clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Botões (opcional) -->
    <div class="mt-4 flex justify-between items-center">
        <p class="text-sm text-gray-400 dark:text-gray-500">
            {{ App\Models\Mudas::whereNull('disabled_at')->count() }}
            {{ Str::plural('muda', $mudas->total()) }}
            <span class="text-emerald-500">Cadastradas</span>
        </p>

        <div class="flex gap-3">
            @if(request()->hasAny(['tipo', 'location', 'search']))
                <a href="{{ route('dashboard') }}"
                   class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    Limpar Filtros
                </a>
            @endif

            <!-- Botão de aplicar filtros, se quiser manter -->
            <button type="submit"
                    class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                Aplicar Filtros
            </button>
        </div>
    </div>
</form>


                    <!-- Após o formulário de filtros, antes do grid -->
                    <div class="flex justify-between items-center mb-6">
                        <!-- Contador de Mudas -->
                        <p class="text-sm text-gray-400 dark:text-gray-500">
                            @if(request()->hasAny(['tipo', 'location', 'search']))
                            {{ $mudas->total() }}
                            {{ Str::plural('muda', $mudas->total()) }}
                            {{-- Se houver algum filtro ativo, mostra "(filtradas)" --}}
                                <span class="text-emerald-500">(encontradas)</span>
                            @endif
                        </p>

                        <!-- Filtros Ativos -->
                        <div class="flex flex-wrap gap-2">
                            @if(request()->has('tipo') && !empty(request()->get('tipo')))
                                @php
                                    $tipoAtual = $tipos->firstWhere('id', request()->get('tipo'));
                                    $queryParamsSemTipo = array_filter(request()->except(['tipo']));
                                    $urlSemTipo = url()->current() . ($queryParamsSemTipo ? '?' . http_build_query($queryParamsSemTipo) : '');
                                @endphp
                                @if($tipoAtual)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-emerald-700 text-white dark:bg-emerald-200 dark:text-emerald-900">
                                        Tipo: {{ $tipoAtual->nome }}
                                        <a href="{{ $urlSemTipo }}" class="ml-1 hover:text-emerald-100 dark:hover:text-emerald-700">
                                            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    </span>
                                @endif
                            @endif

                            @if(request()->has('location') && !empty(request()->get('location')))
                                @php
                                    $queryParamsSemLocation = array_filter(request()->except(['location']));
                                    $urlSemLocation = url()->current() . ($queryParamsSemLocation ? '?' . http_build_query($queryParamsSemLocation) : '');
                                @endphp
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-emerald-700 text-white dark:bg-emerald-200 dark:text-emerald-900">
                                    Local: {{ request()->get('location') }}
                                    <a href="{{ $urlSemLocation }}" class="ml-1 hover:text-emerald-100 dark:hover:text-emerald-700">
                                        <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                </span>
                            @endif

                            @if(request()->has('search') && !empty(request()->get('search')))
                                @php
                                    $queryParamsSemSearch = array_filter(request()->except(['search']));
                                    $urlSemSearch = url()->current() . ($queryParamsSemSearch ? '?' . http_build_query($queryParamsSemSearch) : '');
                                @endphp
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-emerald-700 text-white dark:bg-emerald-200 dark:text-emerald-900">
                                    Busca: {{ request()->get('search') }}
                                    <a href="{{ $urlSemSearch }}" class="ml-1 hover:text-emerald-100 dark:hover:text-emerald-700">
                                        <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Grid de Mudas com Skeleton -->
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @if($mudas->isEmpty())
                            <div class="col-span-full text-center py-8">
                                <p class="text-gray-400 dark:text-neutral-600">Nenhuma muda encontrada</p>
                            </div>
                        @else
                            @foreach($mudas as $muda)
                                <div class="group relative bg-neutral-700 dark:bg-neutral-300 border border-neutral-600 dark:border-neutral-400 rounded-xl shadow-sm hover:shadow-lg transition-shadow duration-300">
                                    <!-- Imagem e Botão Favorito -->
                                    <div class="relative">
                                        @if($muda->foto_url)
                                            @php
                                                $filename = pathinfo($muda->foto_url, PATHINFO_BASENAME);
                                            @endphp
                                            <img class="w-full h-52 object-cover rounded-t-xl"
                                                src="{{ route('mudas.getFileImage', ['filename' => $filename]) }}"
                                                alt="{{ $muda->nome }}">
                                        @else
                                            <div class="w-full h-52 bg-neutral-600/50 dark:bg-neutral-400/50 flex items-center justify-center rounded-t-xl">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-emerald-500/70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </div>
                                        @endif

                                        <!-- Botão Favorito (exemplo de placeholder) -->
                                        @php
                                            $isFavorited = auth()->check() && $muda->favoritedBy->contains(auth()->id());
                                        @endphp
                                        <button class="favorite-btn absolute top-4 right-4 p-2 rounded-full bg-white/80 hover:bg-white dark:bg-neutral-800/80 dark:hover:bg-neutral-800 transition-colors"
                                            data-muda-id="{{ $muda->id }}"
                                            aria-pressed="{{ $isFavorited ? 'true' : 'false' }}"
                                            title="{{ $isFavorited ? 'Remover dos favoritos' : 'Adicionar aos favoritos' }}">
                                            <svg class="w-5 h-5 transition-colors {{ $isFavorited ? 'text-red-500' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                        </button>
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
                                            @php
                                                if($muda->donated_to && auth()->id() == $muda->donated_to) {
                                                    $displayStatus = 'Reservada';
                                                    $badgeClasses = 'bg-blue-100 text-blue-800';
                                                } elseif($muda->donated_at && auth()->id() == $muda->user_id) {
                                                    $displayStatus = 'Doada';
                                                    $badgeClasses = 'bg-yellow-100 text-yellow-800';
                                                } else {
                                                    $displayStatus = $muda->status->nome ?? 'Indisponível';
                                                    if($displayStatus === 'Disponível') {
                                                        $badgeClasses = 'bg-green-100 text-green-800';
                                                    } elseif($displayStatus === 'Indisponível') {
                                                        $badgeClasses = 'bg-red-100 text-red-800';
                                                    } else {
                                                        $badgeClasses = 'bg-yellow-100 text-yellow-800';
                                                    }
                                                }
                                            @endphp
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $badgeClasses }}">
                                                {{ $displayStatus }}
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
                                        class="w-full inline-flex justify-center items-center gap-2 rounded-lg border border-emerald-500/50 font-semibold text-emerald-500 hover:text-white hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all text-sm py-3 px-4 dark:border-emerald-400/50 dark:hover:bg-emerald-500 dark:hover:text-white dark:focus:ring-offset-gray-800">
                                            Ver detalhes
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Paginação -->
                            <div class="col-span-full mt-6">
                                <div class="flex justify-center">
                                    {{ $mudas->links('vendor.pagination.MudaMundo')->with([
                                        'class'    => 'relative z-0 inline-flex shadow-sm rounded-md',
                                        'active'   => 'bg-emerald-500 text-white',
                                        'disabled' => 'opacity-50 cursor-not-allowed',
                                        'link'     => 'px-4 py-2 text-sm font-medium text-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-800/30',
                                        'separator'=> 'px-4 py-2 text-sm font-medium text-gray-400',
                                    ]) }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Para os selects (tipo e localização), o submit automático já funciona
        document.querySelectorAll('select[name="tipo"], select[name="location"]').forEach(select => {
            select.addEventListener('change', function() {
                this.closest('form').submit();
            });
        });

        // 2. Para o campo de busca, atualiza a URL automaticamente com debounce de 500ms
        const searchInput = document.querySelector('input[name="search"]');
        let searchTimeout;
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const value = this.value.trim();
                    const url = new URL(window.location.href);
                    if (value.length > 0) {
                        url.searchParams.set('search', value);
                    } else {
                        url.searchParams.delete('search');
                    }
                    window.location.href = url.toString();
                }, 500);
            });
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                }
            });
        }

        // Função para atualizar a lista de favoritos via AJAX
        function atualizarFavoritos() {
            const favoritosList = document.getElementById('favoritos-list');
            if (!favoritosList) return;
            favoritosList.innerHTML = '<div class="col-span-full text-center py-8"><span class="loader inline-block w-8 h-8 border-4 border-emerald-500 border-t-transparent rounded-full animate-spin"></span></div>';
            fetch('/dashboard/favoritos-html', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.text())
            .then(html => {
                favoritosList.innerHTML = html;
            });
        }

        // Delegação de eventos para botões de favoritos
        document.body.addEventListener('click', function(e) {
            const btn = e.target.closest('.favorite-btn');
            if (!btn) return;
            e.preventDefault();
            const mudaId = btn.getAttribute('data-muda-id');
            const isFavorited = btn.getAttribute('aria-pressed') === 'true';
            const url = `/mudas/${mudaId}/favorite`;
            let fetchOptions = {
                method: isFavorited ? 'DELETE' : 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: isFavorited ? undefined : JSON.stringify({})
            };
            fetch(url, fetchOptions)
            .then(res => res.json())
            .then(data => {
                if (data.favorited !== undefined) {
                    document.querySelectorAll('.favorite-btn[data-muda-id="'+mudaId+'"]').forEach(function(favBtn) {
                        favBtn.setAttribute('aria-pressed', data.favorited ? 'true' : 'false');
                        favBtn.title = data.favorited ? 'Remover dos favoritos' : 'Adicionar aos favoritos';
                        const svg = favBtn.querySelector('svg');
                        if (svg) {
                            svg.classList.toggle('text-red-500', data.favorited);
                            svg.classList.toggle('text-gray-400', !data.favorited);
                        }
                    });
                    atualizarFavoritos();
                }
            });
        });
    });
    </script>
    @endpush
</x-app-layout>
