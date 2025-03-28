<?php

namespace App\Http\Controllers;

use App\Models\Mudas;
use App\Models\Tipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {
            $tipos = Tipo::orderBy('nome')->get();
            $estados = ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'];

            $query = Mudas::query()
                ->with(['tipo', 'status', 'user'])
                ->whereNull('disabled_at')
                ->orderBy('created_at', 'desc');

            // Aplicar busca mesmo que esteja vazia
            if ($request->has('search')) {
                $search = $request->get('search', '');
                $query->where(function($q) use ($search) {
                    $q->where('nome', 'like', "%{$search}%")
                    ->orWhere('descricao', 'like', "%{$search}%");
                });
            }

            if ($request->filled('tipo')) {
                if ($tipo = Tipo::find($request->tipo)) {
                    $query->where('tipos_id', $tipo->id);
                }
            }

            if ($request->filled('location')) {
                $query->where('uf', $request->location);
            }

            $mudas = $query->paginate(12)->onEachSide(1)->withQueryString();
            $favoritos = Mudas::whereNull('disabled_at')->take(4)->latest()->get();

            return view('dashboard', compact('mudas', 'favoritos', 'tipos', 'estados'));

        } catch (\Exception $e) {
            Log::error('Erro no DashboardController@index: ' . $e->getMessage());
            return back()->with('error', 'Ocorreu um erro ao carregar o dashboard.');
        }
    }
}
