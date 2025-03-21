<?php

namespace App\Http\Controllers;

use App\Models\Mudas;
use App\Models\Tipo;
use App\Models\MudaStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Carregar dados básicos
            $tipos = Tipo::orderBy('nome')->get();
            $estados = ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'];

            // Mudas disponíveis
            $query = Mudas::with(['tipo', 'status', 'user'])
                ->whereNull('disabled_at');

            // Aplicar filtros
            if ($request->filled('tipo')) {
                $query->where('tipo_id', $request->tipo);
            }

            if ($request->filled('location')) {
                $query->where('uf', $request->location);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nome', 'like', "%{$search}%")
                    ->orWhere('descricao', 'like', "%{$search}%");
                });
            }

            // Executar query principal
            $mudas = $query->latest()->paginate(12)->withQueryString();

            // Carregar favoritos do usuário atual
            $favoritos = Mudas::whereNull('disabled_at')
                ->take(4)
                ->latest()
                ->get();

            return view('dashboard', compact('mudas', 'favoritos', 'tipos', 'estados'));

        } catch (\Exception $e) {
            Log::error('Erro no DashboardController@index: ' . $e->getMessage());
            return back()->with('error', 'Ocorreu um erro ao carregar o dashboard.');
        }
    }
}
