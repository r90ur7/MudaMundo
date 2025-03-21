<?php

namespace App\Http\Controllers;

use App\Models\Mudas;
use App\Models\Tipo;
use App\Models\MudaStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
            // Carregar dados dos filtros
            $tipos = Tipo::orderBy('nome')->get();
            $status = MudaStatus::orderBy('nome')->get();
            $estados = ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'];

            // Construir a query base
            $query = Mudas::query()
                ->with(['tipo', 'status'])
                ->where('disabled_at', null);

            // Aplicar filtros
            if ($request->filled('tipo')) {
                $query->where('tipo_id', $request->tipo);
            }

            if ($request->filled('location')) {
                $query->where('uf', $request->location);
            }

            if ($request->filled('status')) {
                $query->where('status_id', $request->status);
            } else {
                $query->where('status_id', 1); // Padrão: apenas disponíveis
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nome', 'like', "%{$search}%")
                    ->orWhere('descricao', 'like', "%{$search}%");
                });
            }

            // Executar a query com paginação
            $mudas = $query->latest()->paginate(12)->appends($request->query());

            // Debug para verificar as variáveis
            if (!isset($tipos)) {
                Log::error('Variável $tipos não está definida');
            }

            // Retornar a view com todos os dados necessários
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
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'tipo_id' => 'required|exists:tipos,id',
            'status_id' => 'required|exists:muda_status,id',
            'cidade' => 'required|string|max:255',
            'uf' => 'required|string|size:2',
            'foto' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('mudas', 'public');
            $validated['foto_url'] = asset('storage/' . $path);
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
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'tipo_id' => 'required|exists:tipos,id',
            'status_id' => 'required|exists:muda_status,id',
            'cidade' => 'required|string|max:255',
            'uf' => 'required|string|size:2',
            'foto' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('mudas', 'public');
            $validated['foto_url'] = asset('storage/' . $path);
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
            $user = \Illuminate\Support\Facades\Auth::user();
            $favorites = $user->favorites()->paginate(12);

            return view('mudas.favorites', compact('favorites'));
        } catch (\Exception $e) {
            Log::error('Erro no MudasController@favorites: ' . $e->getMessage());
            return back()->withErrors(['favoritos' => 'Não foi possível carregar seus favoritos.']);
        }
    }
}
