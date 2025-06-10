<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'MudaMundo') }}</title>
    <meta name="robots" content="max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="description" content="Crafted for agencies and studios specializing in web design and development.">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://preline.co/assets/css/main.min.css">
    <script>
        window.userId = {{ auth()->id() ?? 'null' }};
    </script>
</head>

<body class="bg-neutral-900 dark:bg-neutral-100 text-white dark:text-black">
    <!-- ========== HEADER ========== -->
    <header class="sticky top-4 inset-x-0 flex flex-wrap md:justify-start md:flex-nowrap z-50 w-full before:absolute before:inset-0 before:max-w-5xl before:mx-2 lg:before:mx-auto before:rounded-6.5 before:bg-neutral-800/30 dark:before:bg-neutral-200/30 before:backdrop-blur-md">
        @include('layouts.navigation')
    </header>

    <!-- Notificação global de chat -->
    @include('components.chat-notification')

    <!-- ========== MAIN CONTENT ========== -->
    <main id="content" class="min-h-screen bg-neutral-900 dark:bg-neutral-100">
        <div class="bg-neutral-900 dark:bg-neutral-100">
            <div class="max-w-7xl mx-auto px-4 xl:px-0 pt-24 lg:pt-32 pb-24">
                <div class="mx-auto bg-neutral-800 dark:bg-neutral-200 rounded-xl p-8">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </main>

    <div vw class="enabled">
        <div vw-access-button class="active"></div>
        <div vw-plugin-wrapper>
            <div class="vw-plugin-top-wrapper"></div>
        </div>
    </div>
        <footer class="relative bg-neutral-900 dark:bg-neutral-100">
        <div class="mx-auto max-w-5xl px-4 pt-16 pb-8 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <!-- Logo e Descrição -->
                <div>
                    <div class="flex items-center">
                        <img src="{{ asset('image/mudaMundo.png') }}" alt="MudaMundo Logo" class="h-12 w-12 rounded-full">
                        <h2 class="ml-3 text-2xl font-bold text-white dark:text-neutral-900">MudaMundo</h2>
                    </div>
                    <p class="mt-4 max-w-md text-gray-300 dark:text-neutral-700">
                        Conectando pessoas através do compartilhamento de mudas, transformando jardins e construindo uma comunidade mais verde.
                    </p>
                    <div class="mt-6 flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-emerald-500 dark:text-neutral-600 dark:hover:text-emerald-600">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-emerald-500 dark:text-neutral-600 dark:hover:text-emerald-600">
                            <span class="sr-only">Instagram</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-8 lg:col-span-2 lg:grid-cols-3">
                    <div>
                        <h3 class="font-bold text-white dark:text-neutral-900">Navegação</h3>
                        <ul class="mt-4 space-y-2">
                            <li>
                                <a href="{{ route('mudas.index') }}" class="text-gray-400 hover:text-emerald-500 dark:text-neutral-600 dark:hover:text-emerald-600">
                                    Encontrar Mudas
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('mudas.create') }}" class="text-gray-400 hover:text-emerald-500 dark:text-neutral-600 dark:hover:text-emerald-600">
                                    Doar Mudas
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-bold text-white dark:text-neutral-900">Ajuda</h3>
                        <ul class="mt-4 space-y-2">
                            <li>
                                <a href="#" class="text-gray-400 hover:text-emerald-500 dark:text-neutral-600 dark:hover:text-emerald-600">
                                    FAQ
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-gray-400 hover:text-emerald-500 dark:text-neutral-600 dark:hover:text-emerald-600">
                                    Contato
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-bold text-white dark:text-neutral-900">Legal</h3>
                        <ul class="mt-4 space-y-2">
                            <li>
                                <a href="#" class="text-gray-400 hover:text-emerald-500 dark:text-neutral-600 dark:hover:text-emerald-600">
                                    Termos de Uso
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-gray-400 hover:text-emerald-500 dark:text-neutral-600 dark:hover:text-emerald-600">
                                    Privacidade
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="mt-12 border-t border-neutral-800 dark:border-neutral-200 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-sm text-gray-400 dark:text-neutral-600">
                        © 2024 MudaMundo. Todos os direitos reservados.
                    </p>
                    <p class="text-sm text-gray-400 dark:text-neutral-600 mt-4 md:mt-0">
                        Feito com 🌱 por amantes da natureza
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <script defer src="https://cdn.jsdelivr.net/npm/preline/dist/index.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
      </script>
    @stack('scripts')
</body>
</html>
