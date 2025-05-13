<aside class="w-full md:w-1/4 max-w-xs md:sticky md:top-6 md:self-start bg-white dark:bg-neutral-900 rounded-2xl p-7 mb-6 md:mb-0 md:mr-8 shadow-xl flex-shrink-0 hidden md:block">
    <div class="mb-7 border-b border-emerald-100 dark:border-emerald-800 pb-5">
        <h3 class="text-base font-bold mb-4 text-emerald-700 dark:text-emerald-200 flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Minhas Mudas
        </h3>
        <div class="flex flex-wrap gap-2">
            <label class="cursor-pointer">
                <input type="radio" name="filter_type" value="all" class="peer hidden" {{ request()->get('filter_type','all') === 'all' ? 'checked' : '' }}>
                <span class="inline-flex items-center px-3 py-1.5 rounded-lg border border-emerald-200 dark:border-emerald-700 bg-emerald-50 dark:bg-emerald-800 text-emerald-700 dark:text-emerald-200 font-medium text-xs peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:border-emerald-600 transition">Todas</span>
            </label>
            <label class="cursor-pointer">
                <input type="radio" name="filter_type" value="favoritas" class="peer hidden" {{ request()->get('filter_type') === 'favoritas' ? 'checked' : '' }}>
                <span class="inline-flex items-center px-3 py-1.5 rounded-lg border border-yellow-200 dark:border-yellow-700 bg-yellow-50 dark:bg-yellow-800 text-yellow-700 dark:text-yellow-200 font-medium text-xs peer-checked:bg-yellow-500 peer-checked:text-white peer-checked:border-yellow-500 transition">Favoritas</span>
            </label>
            <label class="cursor-pointer">
                <input type="radio" name="filter_type" value="cadastradas" class="peer hidden" {{ request()->get('filter_type') === 'cadastradas' ? 'checked' : '' }}>
                <span class="inline-flex items-center px-3 py-1.5 rounded-lg border border-blue-200 dark:border-blue-700 bg-blue-50 dark:bg-blue-800 text-blue-700 dark:text-blue-200 font-medium text-xs peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition">Cadastradas</span>
            </label>
            <label class="cursor-pointer">
                <input type="radio" name="filter_type" value="transferidas" class="peer hidden" {{ request()->get('filter_type') === 'transferidas' ? 'checked' : '' }}>
                <span class="inline-flex items-center px-3 py-1.5 rounded-lg border border-purple-200 dark:border-purple-700 bg-purple-50 dark:bg-purple-800 text-purple-700 dark:text-purple-200 font-medium text-xs peer-checked:bg-purple-600 peer-checked:text-white peer-checked:border-purple-600 transition">Transferidas</span>
            </label>
            <label class="cursor-pointer">
                <input type="radio" name="filter_type" value="desabilitadas" class="peer hidden" {{ request()->get('filter_type') === 'desabilitadas' ? 'checked' : '' }}>
                <span class="inline-flex items-center px-3 py-1.5 rounded-lg border border-red-200 dark:border-red-700 bg-red-50 dark:bg-red-800 text-red-700 dark:text-red-200 font-medium text-xs peer-checked:bg-red-600 peer-checked:text-white peer-checked:border-red-600 transition">Desabilitadas</span>
            </label>
        </div>
    </div>
    <form id="filtros-mudas-form" class="space-y-6">
        <input type="hidden" name="filter_type" value="{{ request()->get('filter_type','all') }}">
        <div>
            <label for="search" class="text-sm font-semibold mb-1 text-gray-700 dark:text-gray-200 flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
                Buscar
            </label>
            <input type="text" name="search" id="search" value="{{ is_array(request('search')) ? request('search')[0] : request('search') }}" class="block w-full rounded-lg border border-emerald-200 dark:border-emerald-700 bg-white dark:bg-neutral-800 text-gray-900 dark:text-white focus:border-emerald-500 focus:ring-emerald-500 px-4 py-2 shadow-sm" placeholder="Buscar por nome ou descrição...">
        </div>
        <div>
            <label for="tipo" class="text-sm font-semibold mb-1 text-gray-700 dark:text-gray-200 flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/></svg>
                Tipo
            </label>
            <select id="tipo" name="tipo" class="block w-full rounded-lg border border-blue-200 dark:border-blue-700 bg-white dark:bg-blue-900 text-gray-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 px-4 py-2 shadow-sm">
                <option value="">Todos</option>
                @foreach($tipos as $tipo)
                    <option value="{{ $tipo->id }}" {{ request()->get('tipo') == $tipo->id ? 'selected' : '' }}>{{ $tipo->nome }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="location" class="text-sm font-semibold mb-1 text-gray-700 dark:text-gray-200 flex items-center gap-2">
                <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 12.414a4 4 0 10-5.657 5.657l4.243 4.243a8 8 0 1011.314-11.314l-4.243 4.243z"/></svg>
                Estado
            </label>
            <select id="location" name="location" class="block w-full rounded-lg border border-purple-200 dark:border-purple-700 bg-white dark:bg-purple-900 text-gray-900 dark:text-white focus:border-purple-500 focus:ring-purple-500 px-4 py-2 shadow-sm">
                <option value="">Todos</option>
                @foreach($estados as $uf)
                    <option value="{{ $uf }}" {{ request()->get('location') == $uf ? 'selected' : '' }}>{{ $uf }}</option>
                @endforeach
            </select>
        </div>
        @if(request()->hasAny(['tipo', 'location', 'search']))
        <div class="flex flex-col gap-2 pt-2">
            <a href="{{ route('profile.edit') }}" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition-colors text-center shadow">Limpar Filtros</a>
            <button type="submit" class="w-full px-4 py-2 bg-emerald-600 text-white rounded-lg font-semibold hover:bg-emerald-700 transition-colors shadow">Aplicar Filtros</button>
        </div>
        @else
        <div class="pt-2">
            <button type="submit" class="w-full px-4 py-2 bg-emerald-600 text-white rounded-lg font-semibold hover:bg-emerald-700 transition-colors shadow">Aplicar Filtros</button>
        </div>
        @endif
    </form>
    <div class="flex md:justify-start justify-center w-full items-center gap-2 py-3 text-white font-medium rounded-lg transition-all">
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
    <button id="openDrawerBtn" class="mb-4 px-4 py-3 bg-emerald-600 text-white rounded-xl w-full flex items-center justify-center gap-2 font-semibold shadow-lg hover:bg-emerald-700 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
        Filtros
    </button>
    <div id="drawerFiltros" class="fixed inset-0 z-50 bg-black/40 hidden">
        <div class="absolute left-0 top-0 h-full w-4/5 max-w-xs bg-white dark:bg-neutral-900 p-7 shadow-2xl flex flex-col rounded-r-2xl">
            <div class="flex justify-between items-center mb-4">
                <span class="text-lg font-bold text-emerald-700 dark:text-emerald-200">Filtros</span>
                <button id="closeDrawerBtn" class="text-gray-700 dark:text-gray-200 text-2xl">&times;</button>
            </div>
            <div class="mb-6 border-b border-emerald-100 dark:border-emerald-800 pb-5">
                <h3 class="text-base font-bold mb-4 text-emerald-700 dark:text-emerald-200 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Minhas Mudas
                </h3>
                <div class="flex flex-wrap gap-2">
                    <label class="cursor-pointer">
                        <input type="radio" name="filter_type_mobile" value="all" class="peer hidden" {{ request()->get('filter_type','all') === 'all' ? 'checked' : '' }}>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg border border-emerald-200 dark:border-emerald-700 bg-emerald-50 dark:bg-emerald-800 text-emerald-700 dark:text-emerald-200 font-medium text-xs peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:border-emerald-600 transition">Todas</span>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="filter_type_mobile" value="favoritas" class="peer hidden" {{ request()->get('filter_type') === 'favoritas' ? 'checked' : '' }}>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg border border-yellow-200 dark:border-yellow-700 bg-yellow-50 dark:bg-yellow-800 text-yellow-700 dark:text-yellow-200 font-medium text-xs peer-checked:bg-yellow-500 peer-checked:text-white peer-checked:border-yellow-500 transition">Favoritas</span>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="filter_type_mobile" value="cadastradas" class="peer hidden" {{ request()->get('filter_type') === 'cadastradas' ? 'checked' : '' }}>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg border border-blue-200 dark:border-blue-700 bg-blue-50 dark:bg-blue-800 text-blue-700 dark:text-blue-200 font-medium text-xs peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition">Cadastradas</span>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="filter_type_mobile" value="transferidas" class="peer hidden" {{ request()->get('filter_type') === 'transferidas' ? 'checked' : '' }}>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg border border-purple-200 dark:border-purple-700 bg-purple-50 dark:bg-purple-800 text-purple-700 dark:text-purple-200 font-medium text-xs peer-checked:bg-purple-600 peer-checked:text-white peer-checked:border-purple-600 transition">Transferidas</span>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="filter_type_mobile" value="desabilitadas" class="peer hidden" {{ request()->get('filter_type') === 'desabilitadas' ? 'checked' : '' }}>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg border border-red-200 dark:border-red-700 bg-red-50 dark:bg-red-800 text-red-700 dark:text-red-200 font-medium text-xs peer-checked:bg-red-600 peer-checked:text-white peer-checked:border-red-600 transition">Desabilitadas</span>
                    </label>
                </div>
            </div>
            <form id="filtros-mudas-form-mobile" class="space-y-6 flex-1 overflow-y-auto">
                <input type="hidden" name="filter_type" value="{{ request()->get('filter_type','all') }}">
                <div>
                    <label for="tipo_mobile" class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/></svg>
                        Tipo
                    </label>
                    <select id="tipo_mobile" name="tipo" class="block w-full rounded-lg border border-blue-200 dark:border-blue-700 bg-white dark:bg-blue-900 text-gray-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 px-4 py-2 shadow-sm">
                        <option value="">Todos</option>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo->id }}" {{ request()->get('tipo') == $tipo->id ? 'selected' : '' }}>{{ $tipo->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="location_mobile" class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1 flex items-center gap-2">
                        <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 12.414a4 4 0 10-5.657 5.657l4.243 4.243a8 8 0 1011.314-11.314l-4.243 4.243z"/></svg>
                        Estado
                    </label>
                    <select id="location_mobile" name="location" class="block w-full rounded-lg border border-purple-200 dark:border-purple-700 bg-white dark:bg-purple-900 text-gray-900 dark:text-white focus:border-purple-500 focus:ring-purple-500 px-4 py-2 shadow-sm">
                        <option value="">Todos</option>
                        @foreach($estados as $uf)
                            <option value="{{ $uf }}" {{ request()->get('location') == $uf ? 'selected' : '' }}>{{ $uf }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="search_mobile" class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
                        Buscar
                    </label>
                    <input type="text" name="search" id="search_mobile" value="{{ is_array(request('search')) ? request('search')[0] : request('search') }}" class="block w-full rounded-lg border border-emerald-200 dark:border-emerald-700 bg-white dark:bg-neutral-800 text-gray-900 dark:text-white focus:border-emerald-500 focus:ring-emerald-500 px-4 py-2 shadow-sm" placeholder="Buscar por nome ou descrição...">
                </div>
                @if(request()->hasAny(['tipo', 'location', 'search']))
                <div class="flex flex-col gap-2 pt-2">
                    <a href="{{ route('profile.edit') }}" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition-colors text-center shadow">Limpar Filtros</a>
                    <button type="submit" class="w-full px-4 py-2 bg-emerald-600 text-white rounded-lg font-semibold hover:bg-emerald-700 transition-colors shadow">Aplicar Filtros</button>
                </div>
                @else
                <div class="pt-2">
                    <button type="submit" class="w-full px-4 py-2 bg-emerald-600 text-white rounded-lg font-semibold hover:bg-emerald-700 transition-colors shadow">Aplicar Filtros</button>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>
