<div class="max-w-6xl mx-auto px-2 sm:px-4 lg:px-8">
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-7" style="grid-template-columns: repeat(auto-fit, minmax(290px, 1fr));">
    @if($mudas->isEmpty())
        <div class="col-span-full text-center py-8">
            <p class="text-gray-400 dark:text-neutral-600">Nenhuma muda encontrada</p>
        </div>
    @else
        @foreach($mudas as $muda)
            <div class="group relative bg-white dark:bg-gray-900 border border-emerald-100 dark:border-emerald-800 rounded-2xl shadow-lg hover:shadow-2xl transition-shadow duration-300 flex flex-col overflow-hidden min-w-[260px] max-w-[350px] w-full mx-auto">
                <div class="relative">
                    @if($muda->foto_url)
                        @php $filename = pathinfo($muda->foto_url, PATHINFO_BASENAME); @endphp
                        <img class="w-full h-52 object-cover rounded-t-2xl transition-transform duration-300 group-hover:scale-105" src="{{ route('mudas.getFileImage', ['filename' => $filename]) }}" alt="{{ $muda->nome }}">
                    @else
                        <div class="w-full h-52 bg-emerald-50 dark:bg-emerald-900 flex items-center justify-center rounded-t-2xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-emerald-400/70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="p-5 flex-1 flex flex-col">
                    <div class="flex justify-between items-start gap-2">
                        <div>
                            <h3 class="text-lg font-bold text-emerald-700 dark:text-emerald-200">{{ $muda->nome }}</h3>
                            <p class="mt-1 text-xs font-semibold text-emerald-500 dark:text-emerald-400 uppercase tracking-wide">{{ $muda->tipo->nome ?? 'Desconhecido' }}</p>
                        </div>
                        @php
                            if($muda->donated_to) {
                                $displayStatus = 'Doada';
                                $badgeClasses = 'bg-yellow-100 text-yellow-800 border-yellow-300';
                            } elseif($muda->donated_at) {
                                $displayStatus = 'Reservada';
                                $badgeClasses = 'bg-blue-100 text-blue-800 border-blue-300';
                            } else {
                                $displayStatus = $muda->status->nome ?? 'Indisponível';
                                if($displayStatus === 'Reservada') {
                                    $badgeClasses = 'bg-blue-100 text-blue-800 border-blue-300';
                                } elseif($displayStatus === 'Doada') {
                                    $badgeClasses = 'bg-yellow-100 text-yellow-800 border-yellow-300';
                                } elseif($displayStatus === 'Disponível') {
                                    $badgeClasses = 'bg-emerald-100 text-emerald-800 border-emerald-300';
                                } elseif($displayStatus === 'Indisponível') {
                                    $badgeClasses = 'bg-red-100 text-red-800 border-red-300';
                                } else {
                                    $badgeClasses = 'bg-gray-100 text-gray-800 border-gray-300';
                                }
                            }
                        @endphp
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold border {{ $badgeClasses }} shadow-sm">
                            {{ $displayStatus }}
                        </span>
                    </div>
                    <p class="mt-3 text-sm text-gray-500 dark:text-gray-300 flex-1">{{ Str::limit($muda->descricao, 100) }}</p>
                    <div class="mt-4 flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        <span>{{ $muda->cidade }}/{{ $muda->uf }}</span>
                        @if($muda->user)
                            <span class="ml-2">{{ strtoupper(substr($muda->user->name ?? 'A', 0, 1)) }}.{{ $muda->user->cidade ? ' - ' . $muda->user->cidade . '/' . $muda->user->uf : '' }}</span>
                        @endif
                    </div>
                </div>
                <div class="p-4 border-t border-emerald-100 dark:border-emerald-800 bg-emerald-50/40 dark:bg-emerald-900/30">
                    <a href="{{ route('mudas.show', $muda) }}"
                    class="w-full inline-flex justify-center items-center gap-2 rounded-lg border border-emerald-500 font-bold text-emerald-600 hover:text-white hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all text-sm py-3 px-4 dark:border-emerald-400 dark:hover:bg-emerald-500 dark:hover:text-white dark:focus:ring-offset-gray-800 shadow">
                        Ver detalhes
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        @endforeach
        <div class="col-span-full mt-6">
            <div class="flex justify-center">
                {{ $mudas->links('vendor.pagination.MudaMundo') }}
            </div>
        </div>
    @endif
  </div>
</div>
