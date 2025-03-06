<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'MudaMundo') }}</title>

    <!-- Meta Tags -->
    <meta name="robots" content="max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="description" content="Crafted for agencies and studios specializing in web design and development.">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://preline.co/assets/css/main.min.css">

    <!-- Theme Script -->
    <script>
        const html = document.querySelector('html');
        const isLightOrAuto = localStorage.getItem('hs_theme') === 'light' || (localStorage.getItem('hs_theme') === 'auto' && !window.matchMedia('(prefers-color-scheme: dark)').matches);
        const isDarkOrAuto = localStorage.getItem('hs_theme') === 'dark' || (localStorage.getItem('hs_theme') === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches);

        if (isLightOrAuto && html.classList.contains('dark')) html.classList.remove('dark');
        else if (isDarkOrAuto && html.classList.contains('light')) html.classList.remove('light');
        else if (isDarkOrAuto && !html.classList.contains('dark')) html.classList.add('dark');
        else if (isLightOrAuto && !html.classList.contains('light')) html.classList.add('light');
    </script>
</head>

<body class="bg-neutral-900 dark:bg-neutral-100 text-white dark:text-black">
    <!-- ========== HEADER ========== -->
    <header class="sticky top-4 inset-x-0 flex flex-wrap md:justify-start md:flex-nowrap z-50 w-full before:absolute before:inset-0 before:max-w-5xl before:mx-2 lg:before:mx-auto before:rounded-6.5 before:bg-neutral-800/30 dark:before:bg-neutral-200/30 before:backdrop-blur-md">
        <nav class="relative max-w-5xl w-full py-2.5 ps-5 pe-2 md:flex md:items-center md:justify-between md:py-0 mx-2 lg:mx-auto">
            <div class="flex items-center justify-between">
                <div class="flex items-center mr-5">
                    <!-- Logo -->
                    <a class="flex-none rounded-md text-xl inline-block font-semibold focus:outline-hidden focus:opacity-80" href="/">
                        <svg class="w-10 h-auto rounded-full overflow-hidden" width="20" height="20" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <image href="{{ asset('image/mudaMundo.png') }}" alt="MudaMundo" class="w-20 h-auto rounded-full" />
                        </svg>
                    </a>
                </div>
                <button type="button" class="hs-dark-mode-active:hidden block hs-dark-mode font-medium text-gray-800 rounded-full hover:bg-gray-200 focus:outline-hidden focus:bg-gray-200 dark:text-neutral-200 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800" data-hs-theme-click-value="dark">
                    <span class="group inline-flex shrink-0 justify-center items-center size-9">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path>
                        </svg>
                    </span>
                </button>
                <button type="button" class="hs-dark-mode-active:block hidden hs-dark-mode font-medium text-gray-800 rounded-full hover:bg-gray-200 focus:outline-hidden focus:bg-gray-200 dark:text-neutral-200 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800" data-hs-theme-click-value="light">
                    <span class="group inline-flex shrink-0 justify-center items-center size-9">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="4"></circle>
                        <path d="M12 2v2"></path>
                        <path d="M12 20v2"></path>
                        <path d="m4.93 4.93 1.41 1.41"></path>
                        <path d="m17.66 17.66 1.41 1.41"></path>
                        <path d="M2 12h2"></path>
                        <path d="M20 12h2"></path>
                        <path d="m6.34 17.66-1.41 1.41"></path>
                        <path d="m19.07 4.93-1.41 1.41"></path>
                        </svg>
                    </span>
                </button>
                <div class="md:hidden">
                    <button type="button" class="hs-collapse-toggle size-8 flex justify-center items-center text-sm font-semibold rounded-full bg-neutral-800 dark:bg-neutral-200 text-white dark:text-black disabled:opacity-50 disabled:pointer-events-none" id="hs-navbar-floating-dark-collapse" aria-controls="hs-navbar-floating-dark" data-hs-collapse="#hs-navbar-floating-dark">
                        <!-- Ícones do menu mobile -->
                    </button>
                </div>
            </div>

            <!-- Collapse -->
            <div id="hs-navbar-floating-dark" class="hs-collapse hidden overflow-hidden transition-all duration-300 basis-full grow md:block">
                <div class="flex flex-col md:flex-row md:items-center md:justify-end gap-y-3 py-2 md:py-0 md:ps-7">
                    <!-- Itens de navegação -->
                    <a class="pe-3 ps-px sm:px-3 md:py-4 text-sm text-white dark:text-black hover:text-neutral-300 dark:hover:text-neutral-700" href="/login">Login</a>
                    <a class="pe-3 ps-px sm:px-3 md:py-4 text-sm text-white dark:text-black hover:text-neutral-300 dark:hover:text-neutral-700" href="/register">Register</a>
                </div>
            </div>
        </nav>
    </header>

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

    <!-- ========== FOOTER ========== -->
    <footer class="relative overflow-hidden bg-neutral-900 dark:bg-neutral-100">
        <div class="relative z-10">
            <div class="w-full max-w-5xl px-4 xl:px-0 py-10 lg:pt-16 mx-auto">
                <div class="inline-flex items-center">
                    <!-- Logo Footer -->
                    <div class="border-s border-neutral-700 dark:border-neutral-300 ps-5 ms-5">
                        <p class="text-sm text-neutral-400 dark:text-neutral-600">© 2024 MudaMundo</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/preline/dist/index.js"></script>
</body>
</html>
