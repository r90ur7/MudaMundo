<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @if($mudas->isEmpty())
        <div class="col-span-full text-center py-8">
            <p class="text-gray-400 dark:text-neutral-600">Nenhuma muda encontrada</p>
        </div>
    @else
        @foreach($mudas as $muda)
            <div class="group relative bg-neutral-700 dark:bg-neutral-300 border border-neutral-600 dark:border-neutral-400 rounded-xl shadow-sm hover:shadow-lg transition-shadow duration-300">
                <div class="relative">
                    @if($muda->foto_url)
                        @php $filename = pathinfo($muda->foto_url, PATHINFO_BASENAME); @endphp
                        <img class="w-full h-52 object-cover rounded-t-xl"
                             src="{{ route('mudas.getFileImage', ['filename' => $filename]) }}"
                             alt="{{ $muda->nome }}">
                    @else
                        <div class="w-full h-52 bg-neutral-600/50 dark:bg-neutral-400/50 flex items-center justify-center rounded-t-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-emerald-500/70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
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
                            if($muda->donated_to) {
                                // Status quando doação aceita
                                $displayStatus = 'Doada';
                                $badgeClasses = 'bg-yellow-100 text-yellow-800';
                            } elseif($muda->donated_at) {
                                // Status quando reserva confirmada
                                $displayStatus = 'Reservada';
                                $badgeClasses = 'bg-blue-100 text-blue-800';
                            } else {
                                // Status padrão pelo nome
                                $displayStatus = $muda->status->nome ?? 'Indisponível';
                                if($displayStatus === 'Reservada') {
                                    $badgeClasses = 'bg-blue-100 text-blue-800';
                                } elseif($displayStatus === 'Doada') {
                                    $badgeClasses = 'bg-yellow-100 text-yellow-800';
                                } elseif($displayStatus === 'Disponível') {
                                    $badgeClasses = 'bg-green-100 text-green-800';
                                } elseif($displayStatus === 'Indisponível') {
                                    $badgeClasses = 'bg-red-100 text-red-800';
                                } else {
                                    $badgeClasses = 'bg-gray-100 text-gray-800';
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
        <div class="col-span-full mt-6">
            <div class="flex justify-center">
                {{ $mudas->links('vendor.pagination.MudaMundo') }}
            </div>
        </div>
    @endif
</div>
