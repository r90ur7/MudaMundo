@php
$usuarioId = auth()->id();
$solicitacoesFeitas = \App\Models\solicitacoes::with(['mudas', 'status', 'tipo'])
    ->where('user_id', $usuarioId)
    ->latest()
    ->get();
$solicitacoesRecebidas = \App\Models\solicitacoes::with(['mudas', 'status', 'tipo', 'user'])
    ->whereHas('mudas', function($q) use ($usuarioId) {
        $q->where('user_id', $usuarioId);
    })
    ->latest()
    ->get();
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <div>
        <h3 class="text-lg font-bold mb-4">Minhas Solicitações</h3>
        @if($solicitacoesFeitas->isEmpty())
            <p class="text-gray-500">Você ainda não fez nenhuma solicitação.</p>
        @else
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($solicitacoesFeitas as $solicitacao)
                    <div class="py-4 flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            <span class="font-semibold">{{ $solicitacao->mudas->nome ?? '-' }}</span>
                            <span class="text-sm text-gray-500 ml-2">({{ $solicitacao->tipo->nome ?? '-' }})</span>
                            <div class="text-gray-600 dark:text-gray-300 text-sm">
                                Status: {{ $solicitacao->status->nome ?? '-' }}<br>
                                Criada em: {{ $solicitacao->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                        <div class="mt-2 md:mt-0">
                            <a href="{{ route('solicitacoes.show', $solicitacao) }}" class="text-emerald-600 hover:underline">Ver detalhes</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    <div>
        <h3 class="text-lg font-bold mb-4">Solicitações Recebidas nas Minhas Mudas</h3>
        @if($solicitacoesRecebidas->isEmpty())
            <p class="text-gray-500">Nenhuma solicitação recebida nas suas mudas.</p>
        @else
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($solicitacoesRecebidas as $solicitacao)
                    <div class="py-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="mb-2">
                            <span class="font-semibold">{{ $solicitacao->mudas->nome ?? '-' }}</span>
                            <span class="text-sm text-gray-500 ml-2">({{ $solicitacao->tipo->nome ?? '-' }})</span>
                            <div class="text-gray-600 dark:text-gray-300 text-sm">
                                Solicitante: {{ $solicitacao->user->name ?? '-' }}<br>
                                Mensagem: {{ $solicitacao->mensagem ?? 'Nenhuma mensagem' }}<br>
                                Status: {{ $solicitacao->status->nome ?? '-' }}<br>
                                Criada em: {{ $solicitacao->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            @if($solicitacao->tipo->nome === 'Doação')
                                <form method="POST" action="{{ route('solicitacoes.accept', $solicitacao) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-3 py-1 bg-emerald-600 text-white rounded-md">Aceitar</button>
                                </form>
                                <form method="POST" action="{{ route('solicitacoes.reject', $solicitacao) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded-md">Rejeitar</button>
                                </form>
                            @elseif($solicitacao->tipo->nome === 'Permuta')
                                <form method="POST" action="{{ route('solicitacoes.accept', $solicitacao) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-3 py-1 bg-emerald-600 text-white rounded-md">Aceitar</button>
                                </form>
                                <form method="POST" action="{{ route('solicitacoes.reject', $solicitacao) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded-md">Rejeitar</button>
                                </form>
                                <button type="button" data-solicitacao-id="{{ $solicitacao->id }}" data-modal-id="profile-negotiate-modal-{{ $solicitacao->id }}" class="px-3 py-1 bg-blue-600 text-white rounded-md open-negotiate-btn">Negociar</button>
                            @endif
                        </div>
                    </div>

                    <!-- Modal de Negociação específico para esta solicitação -->
                    <div id="profile-negotiate-modal-{{ $solicitacao->id }}" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50">
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
