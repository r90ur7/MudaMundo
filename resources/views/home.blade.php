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
    <!-- Hero Section -->
    <section class="relative overflow-hidden">
        <div class="container mx-auto py-10 sm:py-24 px-4">
            <div class="text-center">
                <h1 class="text-4xl sm:text-6xl font-bold text-gray-800 dark:text-emerald-400">
                    Muda Mundo
                </h1>
                <p class="mt-3 text-xl text-gray-600 dark:text-gray-400">
                    Conectando pessoas através do compartilhamento de mudas
                </p>
                <div class="mt-8 flex justify-center gap-3">
                    <a href="{{ route('mudas.create') }}" class="btn btn-primary gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Doar uma muda
                    </a>
                    <a href="{{ route('mudas.index') }}" class="btn btn-secondary gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                        Encontrar mudas
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Mudas Recentes -->
    <section class="container mx-auto py-10 px-4">
        <div class="text-center mb-10">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                Mudas Disponíveis
            </h2>
        </div>

        {{-- <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($mudas_recentes as $muda)
            <div class="card group h-full bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-slate-900 dark:border-gray-700 dark:shadow-slate-700/[.7] hover:shadow-lg transition-shadow duration-300">
                <div class="card-img relative">
                    <img class="w-full h-52 object-cover rounded-t-xl"
                        src="{{ $muda->foto_url ?? "https://images.unsplash.com/photo-1491147334573-44cbb4602074?q=80&w=2574&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" }}"
                        alt="{{ $muda->nome }}">
                    <span class="badge badge-info absolute top-2 right-2">
                        {{ $muda->tipo->nome ?? 'Desconhecido' }}
                    </span>
                </div>
                <div class="card-body p-4 md:p-6">
                    <div class="flex justify-between items-center">
                        <h3 class="card-title text-xl font-semibold text-gray-800 dark:text-gray-300">
                            {{ $muda->nome }}
                        </h3>
                        <span class="badge {{ $muda->status?->nome === 'Disponível' ? 'badge-success' : 'badge-warning' }}">
                            {{ $muda->status->nome ?? 'Indisponível' }}
                        </span>
                    </div>
                    <p class="mt-3 text-gray-500">
                        {{ Str::limit($muda->descricao, 100) }}
                    </p>
                    <div class="mt-4 flex items-center gap-2 text-sm text-gray-500">
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
                <div class="card-footer border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('mudas.show', $muda) }}" class="btn btn-outline w-full">
                        Ver detalhes
                    </a>
                </div>
            </div>
            @endforeach
        </div> --}}
        <div class="max-w-6xl mx-auto px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 auto-rows-min">
                @foreach ($mudas_recentes as $index => $muda)
                @php
                    $sizeClass = $index === 0 ? 'sm:col-span-2' : '';
                @endphp

                <div class="{{ $sizeClass }}">
                    <a href="{{ route('mudas.show', $muda) }}"
                    class="group relative block h-full overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition-shadow duration-300 bg-white dark:bg-slate-900 dark:border-gray-700">

                        <div class="relative h-full w-full flex flex-col">
                            <!-- Container da imagem com altura dinâmica -->
                            <div class="flex-1 overflow-hidden">
                                <img
                                    class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-105"
                                    src="{{ $muda->foto_url ?? $unsplashImages[array_rand($unsplashImages)] }}"
                                    alt="{{ $muda->nome }}">
                            </div>

                            <!-- Overlay gradiente -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                            <!-- Conteúdo do card -->
                            <div class="absolute bottom-0 left-0 right-0 p-4">
                                <h3 class="text-lg font-semibold text-white drop-shadow-md">
                                    {{ $muda->nome }}
                                </h3>

                                <div class="mt-1 flex flex-wrap items-center gap-2">
                                    <span class="text-sm text-gray-200">
                                        {{ $muda->cidade }}/{{ $muda->uf }}
                                    </span>
                                    <span class="inline-block bg-blue-600 text-white text-xs font-medium py-1 px-2 rounded">
                                        {{ $muda->tipo->nome ?? 'Tipo não definido' }}
                                    </span>
                                </div>

                                @if($index === 0)
                                    <p class="mt-2 text-xs text-gray-100 leading-snug">
                                        {{ Str::limit($muda->descricao, 80) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>


        <div class="mt-10 text-center">
            <a href="{{ route('mudas.index') }}" class="btn btn-outline gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
                Ver todas as mudas
            </a>
        </div>
    </section>
</x-app-layout>
