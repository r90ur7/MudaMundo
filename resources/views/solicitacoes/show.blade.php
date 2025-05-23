<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Detalhes da Solicitação') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 shadow rounded-lg p-6">
                @if(session('success'))
                    <div class="mb-4 p-3 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4">{{ $solicitacao->mudas->nome }}</h3>
                <p class="text-gray-600 dark:text-gray-300 mb-6">{{ Str::limit($solicitacao->mudas->descricao, 150) }}</p>

                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-700 dark:text-gray-300">Tipo de Solicitação:</span>
                        @php
                            $modo = $solicitacao->tipo->nome ?? ($solicitacao->mudas->modo_solicitacao ?? 'Doação');
                        @endphp
                        <span class="text-gray-900 dark:text-gray-100">{{ ucfirst($modo) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-700 dark:text-gray-300">Status:</span>
                        @php
                            if (!is_null($solicitacao->confirmed_at)) {
                                $badgeLabel = 'Reservada';
                                $badgeClasses = 'bg-yellow-100 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-100';
                            } elseif ($solicitacao->status->nome === 'Aceita') {
                                $badgeLabel = 'Doada';
                                $badgeClasses = 'bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100';
                            } else {
                                $badgeLabel = $solicitacao->status->nome;
                                $badgeClasses = 'bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100';
                            }
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeClasses }}">{{ $badgeLabel }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-700 dark:text-gray-300">Solicitante:</span>
                        <span class="text-gray-900 dark:text-gray-100">{{ $solicitacao->user->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-700 dark:text-gray-300">Criada em:</span>
                        <span class="text-gray-900 dark:text-gray-100">{{ $solicitacao->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($solicitacao->rejected_at)
                    <div class="flex justify-between">
                        <span class="font-medium text-red-600">Rejeitada em:</span>
                        <span class="text-red-600">{{ $solicitacao->rejected_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                    @if($solicitacao->finished_at)
                    <div class="flex justify-between">
                        <span class="font-medium text-green-600">Finalizada em:</span>
                        <span class="text-green-600">{{ $solicitacao->finished_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                </div>

                <div class="mt-6 flex justify-end">
                    @if(auth()->id() == $solicitacao->user_id && $solicitacao->status->nome === 'Aceita' && is_null($solicitacao->confirmed_at))
                        <form action="{{ route('solicitacoes.confirm', $solicitacao) }}" method="POST" class="mr-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                {{ __('Confirmar Recebimento') }}
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-300 dark:hover:bg-gray-600">
                        {{ __('Voltar ao Dashboard') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
