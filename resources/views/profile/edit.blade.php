<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Perfil') }}
        </h2>
    </x-slot>
    <div class="py-12" x-data="{ activeTab: 'account' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 shadow-lg rounded-lg overflow-hidden mb-6">
                <div class="p-6 flex flex-col md:flex-row items-center">
                    <div class="flex flex-col items-center md:items-start md:mr-8">
                        <div class="h-32 w-32 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center text-4xl text-gray-500 dark:text-gray-300 mb-4 overflow-hidden">
                            @if(Auth::user()->foto_url)
                                <img src="{{ route('profile.photo', ['filename' => Auth::user()->foto_url]) }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                            @else
                                {{ substr(Auth::user()->name, 0, 1) }}
                            @endif
                        </div>
                        <button type="button" id="openProfilePhotoModal" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                            Trocar Foto
                        </button>
                    </div>
                    <div class="text-center md:text-left mt-4 md:mt-0 flex-grow">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ Auth::user()->name }}</h2>
                        <p class="text-gray-600 dark:text-gray-400">{{ Auth::user()->email }}</p>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Mudas Doadas</h3>
                                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">0</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Mudas Recebidas</h3>
                                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">0</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Mudas Cadastradas</h3>
                                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">0</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-900 shadow-lg rounded-lg overflow-hidden">
                <div class="flex border-b border-gray-200 dark:border-gray-700">
                    <button @click.prevent="activeTab = 'account'"
                        class="px-6 py-3 font-semibold transition-all"
                        :class="{'text-emerald-500 border-b-2 border-emerald-500': activeTab === 'account', 'text-gray-700 dark:text-gray-300': activeTab !== 'account'}">
                        Minha Conta
                    </button>
                    <button @click.prevent="activeTab = 'mudas'"
                        class="px-6 py-3 font-semibold transition-all"
                        :class="{'text-emerald-500 border-b-2 border-emerald-500': activeTab === 'mudas', 'text-gray-700 dark:text-gray-300': activeTab !== 'mudas'}">
                        Mudas
                    </button>
                    <button @click.prevent="activeTab = 'chats'"
                        class="px-6 py-3 font-semibold transition-all"
                        :class="{'text-emerald-500 border-b-2 border-emerald-500': activeTab === 'chats', 'text-gray-700 dark:text-gray-300': activeTab !== 'chats'}">
                        Chats
                    </button>
                    <button @click.prevent="activeTab = 'historico'"
                        class="px-6 py-3 font-semibold transition-all"
                        :class="{'text-emerald-500 border-b-2 border-emerald-500': activeTab === 'historico', 'text-gray-700 dark:text-gray-300': activeTab !== 'historico'}">
                        Histórico
                    </button>
                    <button @click.prevent="activeTab = 'solicitacoes'"
                        class="px-6 py-3 font-semibold transition-all"
                        :class="{'text-emerald-500 border-b-2 border-emerald-500': activeTab === 'solicitacoes', 'text-gray-700 dark:text-gray-300': activeTab !== 'solicitacoes'}">
                        Minhas Solicitações
                    </button>
                </div>

                <div class="p-6">
                    <div x-show="activeTab === 'account'" class="space-y-6">
                        <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-lg shadow">
                            @include('profile.partials.update-profile-information-form')
                        </div>

                        <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-lg shadow">
                            @include('profile.partials.update-password-form')
                        </div>

                        <div class="p-6 bg-red-50 dark:bg-gray-800 dark:text-red-100 rounded-lg shadow">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>

                    <div x-show="activeTab === 'mudas'" class="space-y-6">
                        <div class="flex flex-col md:flex-row">
                            @include('profile.partials.sidebar-filtros', ['tipos' => $tipos, 'estados' => $estados])
                            <div class="flex-1" id="mudas-cards-container">
                                @include('mudas.partials.cards', ['mudas' => $mudas])
                            </div>
                        </div>
                    </div>

                    <div x-show="activeTab === 'chats'" class="space-y-6">
                        <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-lg shadow">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Minhas Conversas</h3>
                            <div class="space-y-4">
                                <p class="text-gray-600 dark:text-gray-400 italic">Você não tem nenhuma conversa ativa no momento.</p>
                                <div class="space-y-2">
                                    <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow hover:bg-gray-50 dark:hover:bg-gray-600 transition cursor-pointer">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 bg-gray-200 dark:bg-gray-800 rounded-full flex items-center justify-center text-sm text-gray-500 dark:text-gray-300 mr-3">
                                                    M
                                                </div>
                                                <div>
                                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100">Maria Silva</h4>
                                                    <p class="text-sm text-gray-600 dark:text-gray-300">Interesse na Jabuticabeira</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-xs text-gray-500 dark:text-gray-400">10/04/2025</span>
                                                <div class="mt-1">
                                                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500">
                                                        <span class="text-xs font-medium text-white">2</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div x-show="activeTab === 'historico'" class="space-y-6">
                        <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-lg shadow">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Histórico de Atividades</h3>

                            <div class="space-y-4">
                                <p class="text-gray-600 dark:text-gray-400 italic">Nenhuma atividade registrada ainda.</p>


                                <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow">
                                    <div class="flex justify-between items-center mb-2">
                                        <h4 class="font-semibold text-gray-900 dark:text-gray-100">Doação de Muda</h4>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">10/04/2025</span>
                                    </div>
                                    <p class="text-gray-600 dark:text-gray-300">Você doou uma muda de Jabuticabeira para Maria.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Aba Solicitações -->
                    <div x-show="activeTab === 'solicitacoes'" class="space-y-6">
                        <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-lg shadow">
                            @include('profile.partials.minhas-solicitacoes')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="profilePhotoModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Trocar Foto de Perfil</h3>
                <button type="button" id="closeProfilePhotoModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            @if($errors->has('foto'))
                <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-md">
                    {{ $errors->first('foto') }}
                </div>
            @endif

            <form id="profilePhotoForm" method="post" action="{{ route('profile.update.photo') }}" class="space-y-4" enctype="multipart/form-data">
                @csrf
                @method('POST')

                <div class="space-y-2">
                    <label for="photo-upload" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Selecione uma nova foto
                    </label>
                    <input type="file" id="photo-upload" name="foto"
                        class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                        accept="image/*" required>
                    <p class="text-xs text-gray-500">Formatos aceitos: JPG, PNG. Tamanho máximo: 50MB.</p>
                </div>

                <div class="mt-4" id="preview-container" style="display: none;">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Preview:</p>
                    <div class="w-full flex justify-center">
                        <div class="w-32 h-32 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-700">
                            <img id="photo-preview" src="#" alt="Preview da foto" class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" id="cancelPhotoUpload" class="px-4 py-2 bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">
                        Salvar Foto
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if (session('status') === 'profile-photo-updated')
    <div id="photoSuccessNotification" class="fixed bottom-4 right-4 bg-emerald-500 text-white p-4 rounded-lg shadow-lg z-50">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <p>Foto de perfil atualizada com sucesso!</p>
        </div>
    </div>
    @endif

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const openModalBtn = document.getElementById('openProfilePhotoModal');
        const closeModalBtn = document.getElementById('closeProfilePhotoModal');
        const cancelBtn = document.getElementById('cancelPhotoUpload');
        const modal = document.getElementById('profilePhotoModal');
        const photoInput = document.getElementById('photo-upload');
        const photoPreview = document.getElementById('photo-preview');
        const previewContainer = document.getElementById('preview-container');

        function openModal() {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            document.getElementById('profilePhotoForm').reset();
            previewContainer.style.display = 'none';
        }

        if(openModalBtn) {
            openModalBtn.addEventListener('click', openModal);
        }

        if(closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
        if(cancelBtn) cancelBtn.addEventListener('click', closeModal);
        if(modal) {
            modal.addEventListener('click', function(e) {
                if(e.target === modal) closeModal();
            });
        }

        if(photoInput) {
            photoInput.addEventListener('change', function() {
                if(this.files && this.files[0]) {
                    const file = this.files[0];

                    // Verificar se o arquivo é uma imagem
                    if(!file.type.match('image.*')) {
                        alert('Por favor, selecione uma imagem.');
                        this.value = '';
                        return;
                    }

                    if(file.size > 50 * 1024 * 1024) {
                        alert('A imagem deve ter no máximo 50MB.');
                        this.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        photoPreview.src = e.target.result;
                        previewContainer.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                }
            });
        }

        const openDrawerBtn = document.getElementById('openDrawerBtn');
        const closeDrawerBtn = document.getElementById('closeDrawerBtn');
        const drawer = document.getElementById('drawerFiltros');
        if(openDrawerBtn && closeDrawerBtn && drawer) {
            openDrawerBtn.addEventListener('click', function() {
                drawer.classList.remove('hidden');
            });
            closeDrawerBtn.addEventListener('click', function() {
                drawer.classList.add('hidden');
            });
            drawer.addEventListener('click', function(e) {
                if(e.target === drawer) drawer.classList.add('hidden');
            });
        }
        const form = document.getElementById('filtros-mudas-form');
        const cardsContainer = document.getElementById('mudas-cards-container');
        if(form && cardsContainer) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                // Considerar filtro de tipo selecionado
                const formData = new FormData(form);
                const selectedFilter = document.querySelector('input[name="filter_type"]:checked');
                if (selectedFilter) formData.set('filter_type', selectedFilter.value);
                const params = new URLSearchParams(formData).toString();
                fetch('{{ route('profile.mudas.filter') }}?' + params, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.json())
                .then(data => {
                    cardsContainer.innerHTML = data.html;
                    window.history.replaceState({}, '', '{{ route('profile.edit') }}?' + params);
                });
            });

            // Auto-aplicar filtros ao mudar selects ou ao pressionar Enter na busca
            const tipoSelect = document.getElementById('tipo');
            const locationSelect = document.getElementById('location');
            const searchInput = document.getElementById('search');
            if(tipoSelect) tipoSelect.addEventListener('change', () => form.dispatchEvent(new Event('submit')));
            if(locationSelect) locationSelect.addEventListener('change', () => form.dispatchEvent(new Event('submit')));
            if(searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if(e.key === 'Enter') {
                        e.preventDefault();
                        form.dispatchEvent(new Event('submit'));
                    }
                });
            }

            // Aplicar filtros ao mudar rádio dentro do form (desktop)
            const desktopFilterRadios = document.querySelectorAll('input[name="filter_type"]');
            desktopFilterRadios.forEach(radio => {
                radio.addEventListener('change', () => {
                    const hidden = form.querySelector('input[name="filter_type"]');
                    if (hidden) hidden.value = radio.value;
                    form.dispatchEvent(new Event('submit'));
                });
            });
        }

        // filtros mobile
        const formMobile = document.getElementById('filtros-mudas-form-mobile');
        if(formMobile && cardsContainer) {
            formMobile.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(formMobile);
                const params = new URLSearchParams(formData).toString();
                fetch('{{ route('profile.mudas.filter') }}?' + params, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.json())
                .then(data => {
                    cardsContainer.innerHTML = data.html;
                    window.history.replaceState({}, '', '{{ route('profile.edit') }}?' + params);
                    drawer.classList.add('hidden');
                });
            });

            // Auto-aplicar filtros mobile ao mudar selects ou ao pressionar Enter na busca
            const tipoMobile = document.getElementById('tipo_mobile');
            const locationMobile = document.getElementById('location_mobile');
            const searchMobile = document.getElementById('search_mobile');
            if(tipoMobile) tipoMobile.addEventListener('change', () => formMobile.dispatchEvent(new Event('submit')));
            if(locationMobile) locationMobile.addEventListener('change', () => formMobile.dispatchEvent(new Event('submit')));
            if(searchMobile) {
                searchMobile.addEventListener('keypress', function(e) {
                    if(e.key === 'Enter') {
                        e.preventDefault();
                        formMobile.dispatchEvent(new Event('submit'));
                    }
                });
            }

            // Aplicar filtros ao mudar rádio dentro do form mobile
            const mobileFilterRadios = document.querySelectorAll('input[name="filter_type_mobile"]');
            mobileFilterRadios.forEach(radio => {
                radio.addEventListener('change', () => {
                    const hidden = formMobile.querySelector('input[name="filter_type"]');
                    if (hidden) hidden.value = radio.value;
                    formMobile.dispatchEvent(new Event('submit'));
                });
            });
        }

        // Auto-remover notificação de sucesso
        const photoSuccessNotification = document.getElementById('photoSuccessNotification');
        if (photoSuccessNotification) {
            setTimeout(() => {
                photoSuccessNotification.remove();
            }, 5000);
        }
    });
    </script>
    @endpush
</x-app-layout>
