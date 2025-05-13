<?php

namespace App\Http\Controllers;

use App\Models\solicitacao_mensagem;
use App\Models\Solicitacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Events\NovaMensagemChat;

class SolicitacaoMensagemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'solicitacao_id' => 'required|exists:solicitacoes,id',
            'mensagem' => 'required|string|max:2000',
        ]);
        $msg = solicitacao_mensagem::create([
            'solicitacao_id' => $request->solicitacao_id,
            'user_id' => Auth::id(),
            'mensagem' => $request->mensagem,
        ]);
        $msg->load('user');
        // Dispara evento para websocket (broadcast)
        broadcast(new NovaMensagemChat($msg))->toOthers();
        return response()->json(['mensagem' => $msg]);
    }

    /**
     * Display the specified resource.
     */
    public function show(solicitacao_mensagem $solicitacao_mensagem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(solicitacao_mensagem $solicitacao_mensagem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, solicitacao_mensagem $solicitacao_mensagem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(solicitacao_mensagem $solicitacao_mensagem)
    {
        //
    }

    /**
     * Retorna todas as mensagens de um chat (solicitação).
     */
    public function chat(Request $request, $solicitacaoId)
    {
        $solicitacao = Solicitacao::with(['user', 'mudas', 'mensagens.user'])->findOrFail($solicitacaoId);
        // Aqui pode-se adicionar verificação de permissão se desejar
        return response()->json([
            'mensagens' => $solicitacao->mensagens()->with('user')->orderBy('created_at')->get(),
            'solicitacao' => $solicitacao
        ]);
    }

    /**
     * Lista todas as conversas (solicitações com mensagens) do usuário autenticado
     */
    public function userChats(Request $request)
    {
        $userId = Auth::id();
        Log::info('[userChats] chamado para user_id: ' . $userId);
        // Busca todas as solicitações em que o usuário está envolvido (como solicitante ou dono da muda)
        $solicitacoes = Solicitacao::where(function($q) use ($userId) {
            $q->where('user_id', $userId)
              ->orWhereHas('mudas', function($q2) use ($userId) {
                  $q2->where('user_id', $userId);
              });
        })
        ->with(['mensagens' => function($q) { $q->latest(); }, 'user', 'mudas.user'])
        ->get();

        Log::info('[userChats] solicitacoes retornadas: ' . $solicitacoes->pluck('id')->join(', '));

        $chats = $solicitacoes->map(function($sol) use ($userId) {
            $lastMsg = $sol->mensagens->first();
            $isSolicitante = $sol->user_id == $userId;
            $otherUser = $isSolicitante
                ? ($sol->mudas && $sol->mudas->user ? $sol->mudas->user : null)
                : $sol->user;
            return [
                'solicitacao' => [
                    'id' => $sol->id,
                    'status' => $sol->status->nome ?? null,
                    'mudaNome' => $sol->mudas ? $sol->mudas->nome : '-',
                ],
                'otherUserName' => $otherUser ? $otherUser->name : 'Usuário',
                'otherUserInitial' => $otherUser ? mb_substr($otherUser->name, 0, 1) : '-',
                'mudaNome' => $sol->mudas ? $sol->mudas->nome : '-',
                'lastMessageDate' => $lastMsg ? $lastMsg->created_at->format('d/m/Y H:i') : null,
                'unreadCount' => 0 // Pode ser implementado depois
            ];
        })
        ->sortByDesc(function($chat) {
            // Ordena por data da última mensagem, chats sem mensagem vão para o final
            return $chat['lastMessageDate'] ? \Carbon\Carbon::createFromFormat('d/m/Y H:i', $chat['lastMessageDate']) : \Carbon\Carbon::createFromTimestamp(0);
        })
        ->values();

        return response()->json(['chats' => $chats]);
    }
}
