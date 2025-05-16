<?php

namespace App\Http\Controllers;

use App\Models\Mudas;
use App\Models\Tipo;
use App\Models\MudaStatus;
use App\Models\Especie;
use App\Models\solicitacao_tipos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class MudasController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {

            $tipos = Tipo::orderBy('nome')->get();
            $status = MudaStatus::orderBy('nome')->get();
            $estados = ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'];

            // Construir a query base
            $query = Mudas::query()
                ->with(['tipo', 'status'])
                ->where('disabled_at', null);


            if ($request->filled('tipo')) {
                $query->where('tipo_id', $request->tipo);
            }

            if ($request->filled('location')) {
                $query->where('uf', $request->location);
            }

            if ($request->filled('status')) {
                $query->where('status_id', $request->status);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nome', 'like', "%{$search}%")
                    ->orWhere('descricao', 'like', "%{$search}%");
                });
            }


            $mudas = $query->latest()->paginate(12)->appends($request->query());


            if (!isset($tipos)) {
                Log::error('Variável $tipos não está definida');
            }


            return view('dashboard', compact('mudas', 'tipos', 'status', 'estados'));

        } catch (\Exception $e) {
            Log::error('Erro no MudasController@index: ' . $e->getMessage());
            return back()->with('error', 'Ocorreu um erro ao carregar as mudas.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tipos = Tipo::orderBy('nome')->get();
        $status = MudaStatus::orderBy('nome')->get();

        return view('mudas.create', compact('tipos', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Log de depuração para verificar dados do request
        Log::info('MudasController@store request data:', $request->all());

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'tipo_nome' => 'required|string|max:255',
            'especie_nome' => 'required|string|max:255',
            'modo_solicitacao' => 'required|in:doacao,permuta',
            'quantidade' => 'nullable|integer|min:1',
            'cep' => 'required|string|size:8',
            'logradouro' => 'required|string|max:150',
            'numero' => 'required|string|max:20',
            'complemento' => 'nullable|string|max:100',
            'bairro' => 'required|string|max:100',
            'cidade' => 'required|string|max:100',
            'uf' => 'required|string|size:2',
            'foto' => 'nullable|image|max:2048',
            'setAsDefault' => 'nullable|boolean'
        ]);

        // Log de dados validados
        Log::info('MudasController@store validated data:', $validated);

        // Persistir modo de solicitação (doacao ou permuta)
        $validated['modo_solicitacao'] = $request->input('modo_solicitacao');

        $validated['user_id'] = Auth::id();

        // Configurar tipo_id para a muda
        $tipo = Tipo::firstOrCreate(
            ['nome' => $validated['tipo_nome']],
            ['descricao' => 'Tipo adicionado pelo usuário']
        );

        // Configurar espécie_id para a muda
        $especie = Especie::firstOrCreate(
            ['nome' => $validated['especie_nome']],
            ['descricao' => 'Espécie adicionada pelo usuário']
        );

        $validated['tipos_id'] = $tipo->id;
        $validated['especie_id'] = $especie->id;

        // Definir o tipo de solicitação (doação ou permuta)
        $solicitacaoTipo = solicitacao_tipos::firstOrCreate(
            ['nome' => $validated['modo_solicitacao'] === 'permuta' ? 'Permuta' : 'Doação'],
            ['descricao' => 'Tipo de solicitação: ' . ($validated['modo_solicitacao'] === 'permuta' ? 'Permuta' : 'Doação')]
        );

        // Guardar o ID do tipo de solicitação para uso na criação de solicitações
        $validated['solicitacao_tipo_id'] = $solicitacaoTipo->id;

        $validated['muda_status_id'] = 1; // Status inicial: Disponível

        unset($validated['tipo_nome'], $validated['especie_nome'], $validated['setAsDefault']);

        if ($request->hasFile('foto')) {
            $path = $this->processImageUpload($request->file('foto'));
            if ($path) {
                $validated['foto_url'] = $path;
                Log::info('Imagem enviada com sucesso para: ' . $path);
            } else {
                return back()->withInput()->withErrors(['foto' => 'Erro ao processar a imagem. Por favor, tente novamente.']);
            }
        }

        if ($request->has('setAsDefault') && $request->setAsDefault == '1') {
            $user = Auth::user();

            $user->update([
                'cep' => $validated['cep'],
                'logradouro' => $validated['logradouro'],
                'numero' => $validated['numero'],
                'complemento' => $validated['complemento'] ?? null,
                'bairro' => $validated['bairro'],
                'cidade' => $validated['cidade'],
                'uf' => $validated['uf']
            ]);
        }

        $muda = Mudas::create($validated);

        return redirect()->route('mudas.show', $muda)
            ->with('success', 'Muda cadastrada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Mudas $muda)
    {
        return view('mudas.show', compact('muda'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mudas $muda)
    {
        $tipos = Tipo::orderBy('nome')->get();
        $status = MudaStatus::orderBy('nome')->get();

        return view('mudas.edit', compact('muda', 'tipos', 'status'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mudas $muda)
    {
        // Permite edição apenas pelo proprietário atual
        if ($muda->donated_at !== null && Auth::id() !== $muda->user_id) {
            abort(403, 'Apenas o novo proprietário pode modificar esta muda.');
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'tipo_id' => 'required|exists:tipos,id',
            'especie_id' => 'required|exists:especies,id',
            'modo_solicitacao' => 'required|in:doacao,permuta',
            'status_id' => 'required|exists:muda_status,id',
            'quantidade' => 'nullable|integer|min:1',
            'cep' => 'required|string|size:8',
            'logradouro' => 'required|string|max:150',
            'numero' => 'required|string|max:20',
            'complemento' => 'nullable|string|max:100',
            'bairro' => 'required|string|max:100',
            'cidade' => 'required|string|max:255',
            'uf' => 'required|string|size:2',
            'foto' => 'nullable|image|max:2048'
        ]);

        // Persistir modo de solicitação (doacao ou permuta)
        $validated['modo_solicitacao'] = $request->input('modo_solicitacao');

        // Processar o tipo de solicitação (doação ou permuta)
        $tipoSolicitacao = $validated['modo_solicitacao'] === 'permuta' ? 'Permuta' : 'Doação';

        // Definir o tipo de solicitação (doação ou permuta)
        $solicitacaoTipo = solicitacao_tipos::firstOrCreate(
            ['nome' => $tipoSolicitacao],
            ['descricao' => 'Tipo de solicitação: ' . $tipoSolicitacao]
        );

        // Guardar o ID do tipo de solicitação para uso nas solicitações
        $validated['solicitacao_tipo_id'] = $solicitacaoTipo->id;

        $validated['tipos_id'] = $validated['tipo_id'];
        $validated['muda_status_id'] = $validated['status_id'];

        // Remove campos que não existem no modelo
        unset($validated['tipo_id'], $validated['status_id']);

        // Processar o upload da foto, se houver
        if ($request->hasFile('foto')) {
            $path = $this->processImageUpload($request->file('foto'));
            if ($path) {
                $validated['foto_url'] = $path;
                Log::info('Imagem atualizada com sucesso em: ' . $path);
            } else {
                return back()->withInput()->withErrors(['foto' => 'Erro ao processar a imagem. Por favor, tente novamente.']);
            }
        }

        $muda->update($validated);

        return redirect()->route('mudas.show', $muda)
            ->with('success', 'Muda atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mudas $muda)
    {
        // Não permite remover se já tiver sido doada/transferida
        if ($muda->donated_at !== null) {
            abort(403, 'Apenas o novo proprietário pode desabilitar esta muda.');
        }

        $muda->update(['disabled_at' => now()]);

        return redirect()->route('mudas.index')
            ->with('success', 'Muda removida com sucesso!');
    }

    /**
     * Display the user's favorites.
     */
    public function favorites(Request $request)
    {
        try {
            $user = Auth::user();
            $favorites = $user->favorites()->paginate(12);

            return view('mudas.favorites', compact('favorites'));
        } catch (\Exception $e) {
            Log::error('Erro no MudasController@favorites: ' . $e->getMessage());
            return back()->withErrors(['favoritos' => 'Não foi possível carregar seus favoritos.']);
        }
    }

    /**
     * Torna a muda novamente disponível para solicitações.
     */
    public function release(Mudas $muda)
    {
        // Apenas o proprietário atual pode liberar a muda
        abort_if(Auth::id() !== $muda->user_id, 403);
        // Resetar flags e status para Disponível
        $muda->update([
            'donated_at'     => null,
            'donated_to'     => null,
            'disabled_at'    => null,
            'muda_status_id' => 1, // Disponível
        ]);
        return back()->with('success', 'Muda novamente disponível.');
    }

    /**
     * Processa o upload de uma imagem e retorna o caminho relativo para salvar no banco.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return string|null
     */
    private function processImageUpload($file)
    {
        if ($file && $file->isValid()) {
            try {
                $filename = $file->getClientOriginalName();
                $newFilename = pathinfo($filename, PATHINFO_FILENAME) . '_' . time() . '.' . $file->getClientOriginalExtension();

                return $file->storeAs('mudas', $newFilename, 'public');

            } catch (\Exception $e) {
                Log::error('Erro ao processar imagem: ' . $e->getMessage());
                return null;
            }
        }

        return null;
    }

    public function getFileImage(Request $req)
    {
        $imageUrl = $req->query('filename');

        $path = storage_path('app/public/mudas/' . $imageUrl);
        if (!file_exists($path)) {
            abort(404);
        }

        $file = file_get_contents($path);
        $type = mime_content_type($path);

        // dd($imageUrl, $file, $type);

        return response($file, 200)->header('Content-Type', $type);
    }

    /**
     * Adiciona uma muda aos favoritos do usuário autenticado.
     */
    public function favorite(Request $request, Mudas $muda)
    {
        try {
            $user = $request->user();
            \Log::info('[FAVORITE] Usuário autenticado:', ['user_id' => $user ? $user->id : null]);
            \Log::info('[FAVORITE] Muda:', ['muda_id' => $muda->id]);
            if (!$user->favorites()->where('muda_id', $muda->id)->exists()) {
                $user->favorites()->attach($muda->id); // Adiciona o favorito no banco
                \Log::info('[FAVORITE] Favorito adicionado com sucesso.', ['user_id' => $user->id, 'muda_id' => $muda->id]);
            } else {
                \Log::info('[FAVORITE] Já favoritado.', ['user_id' => $user->id, 'muda_id' => $muda->id]);
            }
            return response()->json(['favorited' => true]);
        } catch (\Throwable $e) {
            \Log::error('[FAVORITE][ERRO] Falha ao favoritar muda', ['exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['favorited' => false, 'error' => 'Erro ao favoritar.'], 500);
        }
    }

    /**
     * Remove uma muda dos favoritos do usuário autenticado.
     */
    public function unfavorite(Request $request, Mudas $muda)
    {
        $user = $request->user();
        Log::info('[UNFAVORITE] Usuário autenticado:', ['user_id' => $user ? $user->id : null]);
        Log::info('[UNFAVORITE] Muda:', ['muda_id' => $muda->id]);
        $user->favorites()->detach($muda->id);
        Log::info('[UNFAVORITE] Favorito removido.', ['user_id' => $user->id, 'muda_id' => $muda->id]);
        // dd('unfavorite executado', $user->favorites()->pluck('muda_id'));
        return response()->json(['favorited' => false]);
    }
}
