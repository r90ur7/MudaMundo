<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Informações básicas -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Descrição/Bio -->
        <div>
            <x-input-label for="descricao" :value="__('Descrição')" />
            <textarea id="descricao" name="descricao" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="3">{{ old('descricao', $user->descricao) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('descricao')" />
        </div>

        <!-- Informações de endereço -->
        <h3 class="font-medium text-lg text-gray-900 border-b border-gray-200 pb-2 mb-4">Endereço</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="cep" :value="__('CEP')" />
                <div class="flex">
                    <x-text-input id="cep" name="cep" type="text" class="mt-1 block w-full" :value="old('cep', $user->cep)" maxlength="8" placeholder="Somente números" />
                    <button type="button" id="buscarCep" class="mt-1 ml-2 inline-flex items-center px-3 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Buscar
                    </button>
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('cep')" />
                <p id="cep-error" class="mt-1 text-sm text-red-600 hidden">CEP não encontrado ou inválido.</p>
            </div>

            <div class="md:col-span-2">
                <x-input-label for="logradouro" :value="__('Logradouro')" />
                <x-text-input id="logradouro" name="logradouro" type="text" class="mt-1 block w-full" :value="old('logradouro', $user->logradouro)" />
                <x-input-error class="mt-2" :messages="$errors->get('logradouro')" />
            </div>

            <div>
                <x-input-label for="numero" :value="__('Número')" />
                <x-text-input id="numero" name="numero" type="text" class="mt-1 block w-full" :value="old('numero', $user->numero)" />
                <x-input-error class="mt-2" :messages="$errors->get('numero')" />
            </div>

            <div>
                <x-input-label for="complemento" :value="__('Complemento')" />
                <x-text-input id="complemento" name="complemento" type="text" class="mt-1 block w-full" :value="old('complemento', $user->complemento)" />
                <x-input-error class="mt-2" :messages="$errors->get('complemento')" />
            </div>

            <div>
                <x-input-label for="bairro" :value="__('Bairro')" />
                <x-text-input id="bairro" name="bairro" type="text" class="mt-1 block w-full" :value="old('bairro', $user->bairro)" />
                <x-input-error class="mt-2" :messages="$errors->get('bairro')" />
            </div>

            <div>
                <x-input-label for="cidade" :value="__('Cidade')" />
                <x-text-input id="cidade" name="cidade" type="text" class="mt-1 block w-full" :value="old('cidade', $user->cidade)" />
                <x-input-error class="mt-2" :messages="$errors->get('cidade')" />
            </div>

            <div>
                <x-input-label for="uf" :value="__('Estado')" />
                <select id="uf" name="uf" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="">Selecione um estado</option>
                    @foreach(['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'] as $estado)
                        <option value="{{ $estado }}" {{ old('uf', $user->uf) == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('uf')" />
            </div>
        </div>

        <!-- Consentimento LGPD -->
        <div class="mb-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="lgpd_consent" value="1" {{ old('lgpd_consent', $user->lgpd_consent ?? false) ? 'checked' : '' }} required>
                <span class="ml-2">Li e concordo com os <a href="{{ url('/terms') }}" target="_blank" class="underline text-emerald-600">Termos de Serviço e Política de Privacidade</a></span>
            </label>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cepInput = document.getElementById('cep');
        const buscarCepBtn = document.getElementById('buscarCep');
        const cepError = document.getElementById('cep-error');

        // Função para formatar o CEP
        function formatarCep(cep) {
            return cep.replace(/[^0-9]/g, '').substring(0, 8);
        }

        // Função para buscar o endereço pelo CEP
        function buscarEnderecoPorCep() {
            const cep = formatarCep(cepInput.value);

            if (cep.length !== 8) {
                cepError.classList.remove('hidden');
                cepError.textContent = 'CEP deve conter 8 dígitos.';
                return;
            }

            // Mostrar indicador de carregamento
            buscarCepBtn.disabled = true;
            buscarCepBtn.textContent = 'Buscando...';
            cepError.classList.add('hidden');

            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (data.erro) {
                        cepError.classList.remove('hidden');
                        cepError.textContent = 'CEP não encontrado.';
                    } else {
                        // Preencher os campos com os dados retornados
                        document.getElementById('logradouro').value = data.logradouro;
                        document.getElementById('bairro').value = data.bairro;
                        document.getElementById('cidade').value = data.localidade;
                        document.getElementById('uf').value = data.uf;

                        // Focar no campo de número para agilizar o preenchimento
                        document.getElementById('numero').focus();
                    }
                })
                .catch(error => {
                    cepError.classList.remove('hidden');
                    cepError.textContent = 'Erro ao buscar o CEP. Tente novamente.';
                    console.error('Erro na busca de CEP:', error);
                })
                .finally(() => {
                    // Restaurar o botão
                    buscarCepBtn.disabled = false;
                    buscarCepBtn.textContent = 'Buscar';
                });
        }

        // Adicionar evento ao botão de busca
        buscarCepBtn.addEventListener('click', buscarEnderecoPorCep);

        // Também buscar ao pressionar Enter no campo de CEP
        cepInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Evitar envio do formulário
                buscarEnderecoPorCep();
            }
        });

        // Formatar o CEP ao digitar
        cepInput.addEventListener('input', function() {
            this.value = formatarCep(this.value);
        });
    });
</script>
@endpush
