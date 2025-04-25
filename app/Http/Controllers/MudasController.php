<?php

namespace App\Http\Controllers;

use App\Models\Mudas;
use App\Models\Tipo;
use App\Models\MudaStatus;
use App\Models\Especie;
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
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'tipo_nome' => 'required|string|max:255',
            'especie_nome' => 'required|string|max:255',
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

        $validated['user_id'] = auth()->id();

        $tipo = Tipo::firstOrCreate(
            ['nome' => $validated['tipo_nome']],
            ['descricao' => 'Tipo adicionado pelo usuário']
        );
        $especie = Especie::firstOrCreate(
            ['nome' => $validated['especie_nome']],
            ['descricao' => 'Espécie adicionada pelo usuário']
        );

        $validated['tipos_id'] = $tipo->id;
        $validated['especie_id'] = $especie->id;

        $validated['muda_status_id'] = 1;

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
            $user = auth()->user();

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
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'tipo_id' => 'required|exists:tipos,id',
            'especie_id' => 'required|exists:especies,id',
            'status_id' => 'required|exists:muda_status,id',
            'quantidade' => 'nullable|integer|min:1',
            'complemento' => 'nullable|string|max:100',
            'cidade' => 'required|string|max:255',
            'uf' => 'required|string|size:2',
            'foto' => 'nullable|image|max:2048'
        ]);
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
}
