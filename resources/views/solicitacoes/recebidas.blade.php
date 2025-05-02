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
                                Solicitante: {{ $solicitacao->user->name }}<br>
                                Status: {{ $solicitacao->status->nome }}
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
