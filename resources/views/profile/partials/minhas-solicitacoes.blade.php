@php
$usuarioId = auth()->id();
$solicitacoesFeitas = \App\Models\Solicitacao::with(['mudas', 'status', 'tipo'])
    ->where('user_id', $usuarioId)
    ->whereNull('finished_at')
    ->whereNull('rejected_at')
    ->latest()
    ->get();
$solicitacoesRecebidas = \App\Models\Solicitacao::with(['mudas', 'status', 'tipo', 'user'])
    ->whereHas('mudas', function($q) use ($usuarioId) {
        $q->where('user_id', $usuarioId);
    })
    ->whereHas('status', function($q) {
        $q->whereIn('nome', ['Pendente', 'Em negociação']);
    })
    ->latest()
    ->get();
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <div>
        <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Minhas Solicitações
        </h3>
        @if($solicitacoesFeitas->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 flex flex-col items-center">
                <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-gray-500">Você ainda não fez nenhuma solicitação.</p>
            </div>
        @else
            <div class="flex flex-col gap-4">
                @foreach($solicitacoesFeitas as $solicitacao)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border-l-4 {{ $solicitacao->status->nome === 'Pendente' ? 'border-yellow-400' : ($solicitacao->status->nome === 'Em negociação' ? 'border-blue-500' : 'border-emerald-600') }} p-5 flex flex-col gap-2 hover:shadow-lg transition">
                        <div class="flex items-center gap-3">
                            <svg class="w-8 h-8 text-emerald-500 transform rotate-[-45deg]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-lg font-semibold">{{ $solicitacao->mudas->nome ?? '-' }}</span>
                            <span class="ml-2 px-2 py-1 rounded text-xs font-medium bg-emerald-100 text-emerald-700">{{ $solicitacao->tipo->nome ?? '-' }}</span>
                        </div>
                        <div class="flex flex-wrap gap-2 items-center text-sm mt-1">
                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium">
                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                Status: <span class="ml-1 font-semibold">{{ $solicitacao->status->nome ?? '-' }}</span>
                            </span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium">
                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Criada em: {{ $solicitacao->created_at->format('d/m/Y H:i') }}
                            </span>
                        </div>
                        <div class="flex justify-end mt-2">
                            <a href="{{ route('solicitacoes.show', $solicitacao) }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-md font-semibold hover:bg-emerald-700 transition">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m6 0l-3-3m3 3l-3 3"/></svg>
                                Ver detalhes
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    <div>
        <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a5 5 0 00-10 0v2a2 2 0 00-2 2v5a2 2 0 002 2h10a2 2 0 002-2v-5a2 2 0 00-2-2z"/></svg>
            Solicitações Recebidas nas Minhas Mudas
        </h3>
        @if($solicitacoesRecebidas->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 flex flex-col items-center">
                <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a5 5 0 00-10 0v2a2 2 0 00-2 2v5a2 2 0 002 2h10a2 2 0 002-2v-5a2 2 0 00-2-2z"/></svg>
                <p class="text-gray-500">Nenhuma solicitação recebida nas suas mudas.</p>
            </div>
        @else
            <div class="flex flex-col gap-4">
                @foreach($solicitacoesRecebidas as $solicitacao)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border-l-4 {{ $solicitacao->status->nome === 'Pendente' ? 'border-yellow-400' : ($solicitacao->status->nome === 'Em negociação' ? 'border-blue-500' : 'border-emerald-600') }} p-5 flex flex-col gap-2 hover:shadow-lg transition">
                        <div class="flex items-center gap-3">
                            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a5 5 0 00-10 0v2a2 2 0 00-2 2v5a2 2 0 002 2h10a2 2 0 002-2v-5a2 2 0 00-2-2z"/></svg>
                            <span class="text-lg font-semibold">{{ $solicitacao->mudas->nome ?? '-' }}</span>
                            <span class="ml-2 px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-700">{{ $solicitacao->tipo->nome ?? '-' }}</span>
                        </div>
                        <div class="flex flex-wrap gap-2 items-center text-sm mt-1">
                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium">
                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Solicitante: <span class="ml-1 font-semibold">{{ $solicitacao->user->name ?? '-' }}</span>
                            </span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium">
                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Criada em: {{ $solicitacao->created_at->format('d/m/Y H:i') }}
                            </span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium">
                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                Status: <span class="ml-1 font-semibold">{{ $solicitacao->status->nome ?? '-' }}</span>
                            </span>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-900 rounded p-3 text-sm text-gray-700 dark:text-gray-200 mt-2">
                            <span class="font-semibold">Mensagem:</span> {{ $solicitacao->mensagem ?? 'Nenhuma mensagem' }}
                        </div>
                        <div class="flex flex-wrap gap-2 justify-end mt-2">
                            @if($solicitacao->tipo->nome === 'Doação' || $solicitacao->tipo->nome === 'Permuta')
                                <form method="POST" action="{{ route('solicitacoes.accept', $solicitacao) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-md font-semibold hover:bg-emerald-700 transition">Aceitar</button>
                                </form>
                                <form method="POST" action="{{ route('solicitacoes.reject', $solicitacao) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md font-semibold hover:bg-red-700 transition">Rejeitar</button>
                                </form>
                            @endif
                            @if($solicitacao->tipo->nome === 'Permuta')
                                <button type="button" data-solicitacao-id="{{ $solicitacao->id }}" data-modal-id="profile-negotiate-modal-{{ $solicitacao->id }}" class="px-4 py-2 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700 transition open-negotiate-btn">Negociar</button>
                            @endif
                        </div>
                    </div>

                    <!-- Modal de Negociação específico para esta solicitação -->
                    <div id="profile-negotiate-modal-{{ $solicitacao->id }}" class="fixed inset-0 bg-black/50 items-center justify-center p-4 z-50 hidden">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-3xl w-full overflow-auto p-6">
                            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Negociar Permuta</h3>
                            <form method="POST" action="{{ route('solicitacoes.negotiate', $solicitacao) }}" class="negotiation-form">
                                @csrf @method('PATCH')
                                <input type="hidden" name="muda_troca_id" class="negotiation-input">
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                                    @foreach($solicitacao->user->mudas()->whereNull('disabled_at')->get() as $minhaMuda)
                                        <div data-id="{{ $minhaMuda->id }}" data-foto="{{ $minhaMuda->foto_url ? route('mudas.getFileImage',['filename'=>pathinfo($minhaMuda->foto_url,PATHINFO_BASENAME)]) : '' }}" class="negotiation-card cursor-pointer border rounded-lg overflow-hidden hover:shadow-lg">
                                            @if($minhaMuda->foto_url)
                                                @php($fname = pathinfo($minhaMuda->foto_url, PATHINFO_BASENAME))
                                                <img src="{{ route('mudas.getFileImage', ['filename' => $fname]) }}" class="w-full h-32 object-cover" alt="{{ $minhaMuda->nome }}" />
                                            @endif
                                            <div class="p-4">
                                                <h4 class="font-medium text-gray-800 dark:text-gray-100">{{ $minhaMuda->nome }}</h4>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="flex justify-end space-x-2">
                                    <button type="button" class="close-negotiate-btn px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-md">Cancelar</button>
                                    <button type="submit" class="negotiation-submit px-4 py-2 bg-blue-600 text-white rounded-md" disabled>Enviar Proposta</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const negBtns = document.querySelectorAll('.open-negotiate-btn');
    const modals = document.querySelectorAll('[id^="profile-negotiate-modal-"]');
    const closeBtns = document.querySelectorAll('.close-negotiate-btn');
    const forms = document.querySelectorAll('.negotiation-form');
    const cards = document.querySelectorAll('.negotiation-card');
    const submitBtns = document.querySelectorAll('.negotiation-submit');

    negBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const modalId = btn.getAttribute('data-modal-id');
            const modal = document.getElementById(modalId);
            modal.classList.remove('hidden');
        });
    });

    closeBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const modal = btn.closest('[id^="profile-negotiate-modal-"]');
            modal.classList.add('hidden');
        });
    });

    cards.forEach(card => {
        card.addEventListener('click', function() {
            const modal = this.closest('[id^="profile-negotiate-modal-"]');
            const input = modal.querySelector('.negotiation-input');
            const submitBtn = modal.querySelector('.negotiation-submit');
            const allCards = modal.querySelectorAll('.negotiation-card');

            allCards.forEach(c => c.classList.remove('border-emerald-500'));
            this.classList.add('border-emerald-500');
            input.value = this.getAttribute('data-id');
            submitBtn.disabled = false;
        });
    });
});
</script>
@endpush
