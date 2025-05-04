<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            Solicitações Recebidas
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 shadow rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Minhas mudas - Solicitações recebidas</h3>
                @forelse($solicitacoes as $solicitacao)
                    <div class="border-b border-gray-200 dark:border-gray-700 py-4 flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            <span class="font-semibold">{{ $solicitacao->mudas->nome }}</span>
                            <span class="text-sm text-gray-500 ml-2">({{ $solicitacao->tipo->nome }})</span>
                            <div class="text-gray-600 dark:text-gray-300 text-sm">
                                @php
                                    if ($solicitacao->status->nome === 'Aceita' && is_null($solicitacao->confirmed_at)) {
                                        $badgeLabel = 'Doada';
                                        $badgeClasses = 'bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100';
                                    } elseif (!is_null($solicitacao->confirmed_at)) {
                                        $badgeLabel = 'Reservada';
                                        $badgeClasses = 'bg-yellow-100 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-100';
                                    } else {
                                        $badgeLabel = $solicitacao->status->nome;
                                        $badgeClasses = 'bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100';
                                    }
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeClasses }}">{{ $badgeLabel }}</span>
                            </div>
                        </div>
                        <div class="mt-2 md:mt-0">
                            <a href="{{ route('solicitacoes.show', $solicitacao) }}" class="text-emerald-600 hover:underline">Ver detalhes</a>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">Nenhuma solicitação recebida ainda.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
