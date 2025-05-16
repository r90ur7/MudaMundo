@forelse($favoritos ?? [] as $muda)
    <div class="group relative bg-neutral-700 dark:bg-neutral-300 border border-neutral-600 dark:border-neutral-400 rounded-xl shadow-sm hover:shadow-lg transition-shadow duration-300">
        <div class="aspect-w-3 aspect-h-2 relative">
            @if($muda->foto_url)
                @php $filename = pathinfo($muda->foto_url, PATHINFO_BASENAME); @endphp
                <img class="w-full h-32 object-cover rounded-t-lg"
                    src="{{ route('mudas.getFileImage', ['filename' => $filename]) }}"
                    alt="{{ $muda->nome }}">
            @else
                <div class="w-full h-32 bg-neutral-600/50 dark:bg-neutral-400/50 flex items-center justify-center rounded-t-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-emerald-500/70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            @endif
            @php
                $isFavorited = auth()->check() && $muda->favoritedBy->contains(auth()->id());
            @endphp
            <button class="favorite-btn absolute top-2 right-2 p-2 rounded-full bg-white/80 hover:bg-white dark:bg-neutral-800/80 dark:hover:bg-neutral-800 transition-colors"
                data-muda-id="{{ $muda->id }}"
                aria-pressed="{{ $isFavorited ? 'true' : 'false' }}"
                title="{{ $isFavorited ? 'Remover dos favoritos' : 'Adicionar aos favoritos' }}">
                <svg class="w-5 h-5 transition-colors {{ $isFavorited ? 'text-red-500' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </button>
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
