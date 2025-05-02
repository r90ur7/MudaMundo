<aside class="w-full md:w-64 md:sticky md:top-6 md:self-start bg-neutral-800 dark:bg-neutral-100 rounded-xl p-6 mb-6 md:mb-0 md:mr-8 shadow flex-shrink-0 hidden md:block">

    <div class="mb-6 border-b border-gray-200 dark:border-neutral-700 pb-5">
        <h3 class="text-sm font-medium mb-3">Minhas Mudas</h3>
        <div class="space-y-2">
            <label class="flex items-center cursor-pointer">
                <input type="radio" name="filter_type" value="all" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 dark:border-neutral-600 rounded" {{ request()->get('filter_type','all') === 'all' ? 'checked' : '' }}>
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Todas</span>
            </label>
            <label class="flex items-center cursor-pointer">
                <input type="radio" name="filter_type" value="favoritas" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 dark:border-neutral-600 rounded" {{ request()->get('filter_type') === 'favoritas' ? 'checked' : '' }}>
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Favoritas</span>
            </label>
            <label class="flex items-center cursor-pointer">
                <input type="radio" name="filter_type" value="cadastradas" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 dark:border-neutral-600 rounded" {{ request()->get('filter_type') === 'cadastradas' ? 'checked' : '' }}>
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Cadastradas</span>
            </label>
            <label class="flex items-center cursor-pointer">
                <input type="radio" name="filter_type" value="transferidas" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 dark:border-neutral-600 rounded" {{ request()->get('filter_type') === 'transferidas' ? 'checked' : '' }}>
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Transferidas</span>
            </label>
            <label class="flex items-center cursor-pointer">
                <input type="radio" name="filter_type" value="desabilitadas" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 dark:border-neutral-600 rounded" {{ request()->get('filter_type') === 'desabilitadas' ? 'checked' : '' }}>
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Desabilitadas</span>
            </label>
        </div>
    </div>

    <form id="filtros-mudas-form" class="space-y-6">
        <input type="hidden" name="filter_type" value="{{ request()->get('filter_type','all') }}">
        <div>
            <label for="search" class="block text-sm font-medium mb-1">Buscar</label>
            <input type="text" name="search" id="search" value="{{ is_array(request('search')) ? request('search')[0] : request('search') }}" class="block w-full rounded-md border-neutral-300 dark:border-neutral-600 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Buscar por nome ou descrição...">
        </div>
        <div>
            <label for="tipo" class="block text-sm font-medium mb-1">Tipo</label>
            <select id="tipo" name="tipo" class="block w-full rounded-md border-neutral-300 dark:border-neutral-600 bg-white dark:bg-neutral-600 text-gray-900 dark:text-white focus:border-emerald-500 focus:ring-emerald-500">
                <option value="">Todos</option>
                @foreach($tipos as $tipo)
                    <option value="{{ $tipo->id }}" {{ request()->get('tipo') == $tipo->id ? 'selected' : '' }}>{{ $tipo->nome }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="location" class="block text-sm font-medium mb-1">Estado</label>
            <select id="location" name="location" class="block w-full rounded-md border-neutral-300 dark:border-neutral-600 bg-white dark:bg-neutral-600 text-gray-900 dark:text-white focus:border-emerald-500 focus:ring-emerald-500">
                <option value="">Todos</option>
                @foreach($estados as $uf)
                    <option value="{{ $uf }}" {{ request()->get('location') == $uf ? 'selected' : '' }}>{{ $uf }}</option>
                @endforeach
            </select>
        </div>
        @if(request()->hasAny(['tipo', 'location', 'search']))
        <div class="flex flex-col gap-2 pt-2">
            <a href="{{ route('profile.edit') }}" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-center">Limpar Filtros</a>
            <button type="submit" class="w-full px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">Aplicar Filtros</button>
        </div>
        @else
        <div class="pt-2">
            <button type="submit" class="w-full px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">Aplicar Filtros</button>
        </div>
        @endif
    </form>
    <div class="flex justify-end md:justify-start w-full flex justify-center items-center gap-2 py-3 text-white font-medium rounded-lg transition-all">
        <a href="{{ route('mudas.create') }}"
            class="inline-flex items-center py-3 px-4 bg-gradient-to-r from-emerald-500 to-emerald-700 text-white font-medium rounded-lg shadow-lg hover:from-emerald-600 hover:to-emerald-800 focus:outline-none focus:ring-4 focus:ring-emerald-500/50 transition-all duration-300 w-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Publicar Nova Muda
        </a>
    </div>
</aside>

<!-- Drawer para mobile -->
<div class="md:hidden">
    <button id="openDrawerBtn" class="mb-4 px-4 py-2 bg-emerald-600 text-white rounded-lg w-full flex items-center justify-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
        Filtros
    </button>
    <div id="drawerFiltros" class="fixed inset-0 z-50 bg-black/40 hidden">
        <div class="absolute left-0 top-0 h-full w-4/5 max-w-xs bg-gray-50 dark:bg-neutral-800 p-6 shadow-xl flex flex-col">
            <div class="flex justify-between items-center mb-4">
                <span class="text-lg font-semibold text-gray-800 dark:text-gray-200">Filtros</span>
                <button id="closeDrawerBtn" class="text-gray-700 dark:text-gray-200 text-2xl">&times;</button>
            </div>

            <!-- Botão para criar mudas -->
            <div class="mb-6">
                <a href="{{ route('mudas.create') }}" class="w-full flex justify-center items-center gap-2 py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Criar Muda
                </a>
            </div>

            <!-- Seletor de tipo de mudas para Mobile -->
            <div class="mb-6 border-b border-gray-200 dark:border-neutral-700 pb-5">
                <h3 class="text-sm font-medium mb-3">Minhas Mudas</h3>
                <div class="space-y-2">
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="filter_type_mobile" value="all" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 dark:border-neutral-600 rounded" {{ request()->get('filter_type','all') === 'all' ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Todas</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="filter_type_mobile" value="favoritas" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 dark:border-neutral-600 rounded" {{ request()->get('filter_type') === 'favoritas' ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Favoritas</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="filter_type_mobile" value="cadastradas" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 dark:border-neutral-600 rounded" {{ request()->get('filter_type') === 'cadastradas' ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Cadastradas</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="filter_type_mobile" value="transferidas" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 dark:border-neutral-600 rounded" {{ request()->get('filter_type') === 'transferidas' ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Transferidas</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="filter_type_mobile" value="desabilitadas" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 dark:border-neutral-600 rounded" {{ request()->get('filter_type') === 'desabilitadas' ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Desabilitadas</span>
                    </label>
                </div>
            </div>

            <form id="filtros-mudas-form-mobile" class="space-y-6 flex-1 overflow-y-auto">
                <input type="hidden" name="filter_type" value="{{ request()->get('filter_type','all') }}">
                <div>
                    <label for="tipo_mobile" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo</label>
                    <select id="tipo_mobile" name="tipo" class="block w-full rounded-md border-neutral-300 dark:border-neutral-600 bg-white dark:bg-neutral-800 text-gray-900 dark:text-white focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Todos</option>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo->id }}" {{ request()->get('tipo') == $tipo->id ? 'selected' : '' }}>{{ $tipo->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="location_mobile" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                    <select id="location_mobile" name="location" class="block w-full rounded-md border-neutral-300 dark:border-neutral-600 bg-white dark:bg-neutral-800 text-gray-900 dark:text-white focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Todos</option>
                        @foreach($estados as $uf)
                            <option value="{{ $uf }}" {{ request()->get('location') == $uf ? 'selected' : '' }}>{{ $uf }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="search_mobile" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar</label>
                    <input type="text" name="search" id="search_mobile" value="{{ is_array(request('search')) ? request('search')[0] : request('search') }}" class="block w-full rounded-md border-neutral-300 dark:border-neutral-600 bg-white dark:bg-neutral-800 text-gray-900 dark:text-white focus:border-emerald-500 focus:ring-emerald-500" placeholder="Buscar por nome ou descrição...">
                </div>
                @if(request()->hasAny(['tipo', 'location', 'search']))
                <div class="flex flex-col gap-2 pt-2">
                    <a href="{{ route('profile.edit') }}" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-center">Limpar Filtros</a>
                    <button type="submit" class="w-full px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">Aplicar Filtros</button>
                </div>
                @else
                <div class="pt-2">
                    <button type="submit" class="w-full px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">Aplicar Filtros</button>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>
