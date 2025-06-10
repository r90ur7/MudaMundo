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
                <h1 class="text-4xl sm:text-6xl font-bold text-gray-400 dark:text-emerald-400">
                    Muda Mundo
                </h1>
                <p class="mt-3 text-lg sm:text-xl text-gray-600 dark:text-gray-400">
                    Conectando pessoas através do compartilhamento de mudas
                </p>
                <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4 w-full max-w-md mx-auto">
                    <!-- Botão Doar uma muda -->
                    <a href="{{ route('mudas.create') }}"
                        class="py-3 px-6 inline-flex items-center gap-x-3 text-sm font-semibold rounded-lg border border-emerald-500 text-emerald-500 hover:bg-emerald-500 hover:text-white disabled:opacity-50 disabled:pointer-events-none dark:border-emerald-400 dark:text-emerald-400 dark:hover:text-white dark:hover:bg-emerald-500 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600 transition duration-300 w-full sm:w-auto justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4 transition-transform duration-300 group-hover:scale-110"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">
                            <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Doar uma muda
                    </a>

                    <!-- Botão Encontrar mudas -->
                    <a href="{{ route('mudas.index') }}"
                        class="py-3 px-6 inline-flex items-center gap-x-3 text-sm font-semibold rounded-lg border border-gray-200 text-gray-500 hover:border-emerald-500 hover:text-emerald-500 disabled:opacity-50 disabled:pointer-events-none dark:border-gray-700 dark:text-gray-400 dark:hover:text-emerald-400 dark:hover:border-emerald-400 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600 transition duration-300 w-full sm:w-auto justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4 transition-transform duration-300 group-hover:scale-110"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">
                            <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                        Encontrar mudas
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Mudas Recentes -->
    <section class="container mx-auto py-8 sm:py-10 px-2 sm:px-4">
        <div class="text-center mb-8 sm:mb-10">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-400 dark:text-emerald-400">
                Mudas Disponíveis
            </h2>
        </div>
        <div class="max-w-6xl mx-auto px-0 sm:px-4 py-6 sm:py-10 lg:px-8 lg:py-14">
            <div class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-2 md:grid-cols-3 gap-4 auto-rows-min">
                @foreach ($mudas_recentes as $index => $muda)
                @php
                    $sizeClass = $index === 0 ? 'md:col-span-2' : '';
                @endphp

                <div class="{{ $sizeClass }}">
                    <a href="{{ route('mudas.show', $muda) }}"
                    class="group relative block h-full overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition-shadow duration-300 bg-white dark:bg-slate-900 dark:border-gray-700">

                        <div class="relative h-full w-full flex flex-col">
                            <!-- Container da imagem com altura dinâmica -->
                            <div class="flex-1 overflow-hidden min-h-[180px] sm:min-h-[220px] md:min-h-[240px] max-h-[320px]">
                                @if($muda->foto_url)
                                    @php
                                        $filename = pathinfo($muda->foto_url, PATHINFO_BASENAME);
                                    @endphp
                                    <img
                                        class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-105"
                                        src="{{ route('mudas.getFileImage', ['filename' => $filename]) }}"
                                        alt="{{ $muda->nome }}">
                                @else
                                    <img
                                        class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-105"
                                        src="{{ $unsplashImages[array_rand($unsplashImages)] }}"
                                        alt="{{ $muda->nome }}">
                                @endif
                            </div>

                            <!-- Overlay gradiente -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                            <!-- Conteúdo do card -->
                            <div class="absolute bottom-0 left-0 right-0 p-3 sm:p-4">
                                <h3 class="text-base sm:text-lg font-semibold text-white drop-shadow-md">
                                    {{ $muda->nome }}
                                </h3>

                                <div class="mt-1 flex flex-wrap items-center gap-2">
                                    <span class="text-xs sm:text-sm text-gray-200">
                                        {{ $muda->cidade }}/{{ $muda->uf }}
                                    </span>
                                    <span class="inline-block bg-blue-600 text-white text-xs font-medium py-1 px-2 rounded">
                                        {{ $muda->tipo->nome ?? 'Tipo não definido' }}
                                    </span>
                                </div>

                                @if($index === 0)
                                    <p class="mt-2 text-xs text-gray-100 leading-snug hidden sm:block">
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

        <div class="mt-10 sm:mt-16">
            <div class="relative">
                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                    <div class="w-full border-t border-gray-400 dark:border-gray-700"></div>
                </div>
                <!-- Botão centralizado sobre o divisor -->
                <div class="relative flex justify-center">
                    <a href="{{ route('dashboard') }}"
                    class="group inline-flex items-center gap-x-4 rounded-full bg-white dark:bg-slate-900 p-3 sm:p-4 shadow-sm shadow-emerald-500/30 hover:shadow-emerald-500/50 transition duration-300 border border-gray-200 dark:border-gray-700 hover:border-emerald-500 dark:hover:border-emerald-500">
                        <span class="inline-flex items-center gap-2 text-xs sm:text-sm font-medium text-gray-400 dark:text-gray-700 group-hover:text-emerald-600 dark:group-hover:text-emerald-400">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5 transition-transform duration-300 group-hover:-translate-y-0.5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                            Explorar Catálogo Completo
                        </span>
                        <span class="inline-flex items-center justify-center size-7 rounded-full bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 group-hover:bg-emerald-100 dark:group-hover:bg-emerald-900/50 transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-0.5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
