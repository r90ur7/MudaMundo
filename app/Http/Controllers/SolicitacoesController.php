<?php

namespace App\Http\Controllers;

use App\Models\Solicitacao;
use App\Models\Mudas;
use App\Models\solicitacao_status;
use App\Models\solicitacao_tipos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use App\Models\MudaStatus;
use App\Events\SolicitacaoCriada;
use App\Events\SolicitacaoRecebida;
use App\Events\SolicitacaoAceita;
use App\Events\SolicitacaoRejeitada;

class SolicitacoesController extends Controller
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
     * Exibe a página de checkout para criar uma nova solicitação
     */
    public function checkout(Mudas $muda)
    {
        return view('solicitacoes.checkout', compact('muda'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $isAjax = $request->ajax();
            Log::info('Dados recebidos na solicitação:', $request->all());
            $validator = Validator::make($request->all(), [
                'muda_id' => 'required|exists:mudas,id',
                'solicitacao_tipos_id' => 'required',
                'muda_troca_id' => 'nullable|exists:mudas,id',
                'mensagem' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                if ($isAjax) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Formulário contém erros. Por favor, corrija-os.',
                        'errors' => $validator->errors()
                    ], 422);
                }
                return back()->withErrors($validator)->withInput();
            }
            $validated = $validator->validated();

            $validated['user_id'] = Auth::id();
            $tipoId = $validated['solicitacao_tipos_id'];
            $tiposCount = solicitacao_tipos::count();
            if ($tiposCount == 0) {
                solicitacao_tipos::create([
                    'id' => 1,
                    'nome' => 'Doação',
                    'descricao' => 'Solicitação de doação de muda'
                ]);

                solicitacao_tipos::create([
                    'id' => 2,
                    'nome' => 'Permuta',
                    'descricao' => 'Solicitação de troca de muda'
                ]);
            }

            $statusInicial = solicitacao_status::where('nome', 'Pendente')->first();
            if (!$statusInicial) {
                $statusInicial = solicitacao_status::create([
                    'nome' => 'Pendente'
                ]);
            }
            $validated['solicitacao_status_id'] = $statusInicial->id;

            $muda = Mudas::findOrFail($validated['muda_id']);
            if ($muda->user_id == Auth::id()) {
                DB::rollBack();
                if ($isAjax) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Você não pode solicitar sua própria muda.'
                    ], 400);
                }
                return back()->withErrors(['error' => 'Você não pode solicitar sua própria muda.'])->withInput();
            }

            $existingSolicitation = Solicitacao::where('muda_id', $validated['muda_id'])
                ->where('user_id', $validated['user_id'])
                ->whereNull('canceled_at')
                ->whereNull('rejected_at')
                ->whereNull('finished_at')
                ->first();

            if ($existingSolicitation) {
                DB::rollBack();
                if ($isAjax) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Você já tem uma solicitação pendente para esta muda.'
                    ], 400);
                }
                return back()->withErrors(['error' => 'Você já tem uma solicitação pendente para esta muda.'])->withInput();
            }

            $hasColumn_muda_troca_id = Schema::hasColumn('solicitacoes', 'muda_troca_id');
            $hasColumn_mensagem = Schema::hasColumn('solicitacoes', 'mensagem');

            try {
                Log::info('Tentando inserir via DB::table');

                $dataToInsert = [
                    'user_id' => $validated['user_id'],
                    'muda_id' => $validated['muda_id'],
                    'solicitacao_tipos_id' => $validated['solicitacao_tipos_id'],
                    'solicitacao_status_id' => $validated['solicitacao_status_id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if ($hasColumn_muda_troca_id && isset($validated['muda_troca_id'])) {
                    $dataToInsert['muda_troca_id'] = $validated['muda_troca_id'];
                }

                if ($hasColumn_mensagem && isset($validated['mensagem'])) {
                    $dataToInsert['mensagem'] = $validated['mensagem'];
                }

                $solicitacaoId = DB::table('solicitacoes')->insertGetId($dataToInsert);

                Log::info('Solicitação inserida com sucesso via query builder. ID: ' . $solicitacaoId);

                // Disparar eventos de notificação para solicitante e dono da muda
                $solicitacao = Solicitacao::with(['mudas', 'user'])->find($solicitacaoId);
                if ($solicitacao) {
                    // Notifica o solicitante (confirmação)
                    broadcast(new SolicitacaoCriada($solicitacao));
                    // Notifica o dono da muda (recebida)
                    broadcast(new SolicitacaoRecebida($solicitacao));
                }

                DB::commit();

                if ($isAjax) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Solicitação enviada com sucesso! Em breve o proprietário entrará em contato.',
                        'redirect' => route('dashboard')
                    ]);
                }

                return redirect()->route('dashboard')
                    ->with('success', 'Solicitação enviada com sucesso! Em breve o proprietário entrará em contato.');

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Erro ao inserir via query builder: ' . $e->getMessage());
                Log::error('Trace: ' . $e->getTraceAsString());


                if ($isAjax) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erro ao salvar a solicitação: ' . $e->getMessage()
                    ], 500);
                }

                return back()->withInput()->withErrors(['error' => 'Erro ao salvar a solicitação: ' . $e->getMessage()]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro geral ao processar solicitação: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ocorreu um erro ao processar sua solicitação: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->withErrors(['error' => 'Ocorreu um erro ao processar sua solicitação: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Solicitacao $solicitacao)
    {
        return view('solicitacoes.show', compact('solicitacao'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Solicitacao $solicitacao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Solicitacao $solicitacao)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Solicitacao $solicitacao)
    {
        //
    }

    /**
     * Aceitar uma solicitação (doação ou permuta).
     */
    public function accept(Solicitacao $solicitacao)
    {
        // Define status Aceita
        $statusAceita = solicitacao_status::firstOrCreate(['nome' => 'Aceita']);
        // Atualiza status da solicitação e data de aceite
        $solicitacao->update([
            'solicitacao_status_id' => $statusAceita->id,
            'accepted_at' => now(),
        ]);

        // Atualiza a muda como reservada (não altera owner nem desabilita)
        $statusReservada = \App\Models\MudaStatus::firstOrCreate(['nome' => 'Reservada']);
        $muda = $solicitacao->mudas;
        // Armazena criador original e marca reserva
        $originalOwner = $muda->user_id;
        $muda->update([
            'original_user_id' => $originalOwner,
            'muda_status_id'   => $statusReservada->id,
            'donated_at'       => now(),
            'donated_to'       => $solicitacao->user_id,
        ]);

        // Notifica o solicitante e o dono da muda
        broadcast(new SolicitacaoAceita($solicitacao));
        broadcast(new SolicitacaoRecebida($solicitacao, 'Você aceitou a solicitação para a muda "' . ($muda->nome ?? '') . '".'));

        return back()->with('success', 'Solicitação aceita com sucesso.');
    }

    /**
     * Rejeitar uma solicitação (doação ou permuta).
     */
    public function reject(Solicitacao $solicitacao)
    {
        // Define status Rejeitada
        $statusRejeitada = solicitacao_status::firstOrCreate(['nome' => 'Rejeitada']);
        $solicitacao->update([
            'solicitacao_status_id' => $statusRejeitada->id,
            'rejected_at' => now(),
        ]);

        // Notifica o solicitante e o dono da muda
        broadcast(new SolicitacaoRejeitada($solicitacao));
        broadcast(new SolicitacaoRecebida($solicitacao, 'Você rejeitou a solicitação para a muda "' . ($solicitacao->mudas->nome ?? '') . '".'));

        return back()->with('success', 'Solicitação rejeitada.');
    }

    /**
     * Negociar permuta alterando a muda de troca.
     */
    public function negotiate(Request $request, Solicitacao $solicitacao)
    {
        // Valida muda_troca_id
        $request->validate(['muda_troca_id' => 'required|exists:mudas,id']);

        // Define status Em negociação
        $statusNegoc = solicitacao_status::firstOrCreate(['nome' => 'Em negociação']);
        $solicitacao->update([
            'muda_troca_id' => $request->muda_troca_id,
            'solicitacao_status_id' => $statusNegoc->id,
        ]);

        return back()->with('success', 'Proposta de permuta atualizada. Em negociação.');
    }

    /**
     * Confirmar recebimento da muda e atualizar inventário.
     */
    public function confirm(Solicitacao $solicitacao)
    {
        // Define status Recebida
        $statusRecebida = solicitacao_status::firstOrCreate(['nome' => 'Recebida']);
        // Atualiza status da solicitação e data de confirmação
        $solicitacao->update([
            'solicitacao_status_id' => $statusRecebida->id,
            'confirmed_at' => now(),
        ]);

        // Reserva e transfere propriedade da(s) muda(s)
        $statusReservada = MudaStatus::firstOrCreate(['nome' => 'Reservada']);
        $mainMuda = $solicitacao->mudas;
        // Armazena criador original e transfere propriedade
        $originalOwner = $mainMuda->user_id;
        $mainMuda->update([
            'original_user_id' => $originalOwner,
            'user_id'          => $solicitacao->user_id,
            'muda_status_id'   => $statusReservada->id,
            'donated_at'       => now(),
            'donated_to'       => $solicitacao->user_id,
        ]);
        // Se for permuta, reserva e transfere também a muda de troca para o dono original
        if ($solicitacao->muda_troca_id && $solicitacao->mudaTroca) {
            $tradeMuda = $solicitacao->mudaTroca;
            // Armazena criador original da troca e transfere propriedade
            $originalTradeOwner = $tradeMuda->user_id;
            $tradeMuda->update([
                'original_user_id' => $originalTradeOwner,
                'user_id'          => $originalOwner,
                'muda_status_id'   => $statusReservada->id,
                'donated_at'       => now(),
                'donated_to'       => $originalOwner,
            ]);
        }

        return back()->with('success', 'Recebimento confirmado e inventário atualizado.');
    }
}
