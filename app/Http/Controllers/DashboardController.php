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
            // Carregar dados básicos
            $tipos = Tipo::orderBy('nome')->get();
            $estados = ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'];

            // Mudas disponíveis
            $query = Mudas::query()
                ->with(['tipo', 'status', 'user'])
                ->whereNull('disabled_at')
                ->orderBy('created_at', 'desc');

            // Aplicar filtros
            if ($request->filled('tipo')) {
                $tipo = Tipo::find($request->tipo);
                if ($tipo) {
                    $query->where('tipos_id', $tipo->id);
                }
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
            $mudas = $query->paginate(12)->onEachSide(1)->withQueryString();
            // dd([
            //     'total_mudas' => $mudas->total(),
            //     'pagina_atual' => $mudas->currentPage(),
            //     'por_pagina' => $mudas->perPage(),
            //     'filtros' => $request->all()
            // ]);

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
