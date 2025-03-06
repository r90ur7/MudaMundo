<?php

namespace App\Http\Controllers;

use App\Models\Mudas;

class HomeController extends Controller
{
    public function index()
    {
        $mudas_recentes = Mudas::with(['user', 'especie'])
            ->where('disabled_at', null)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('home', compact('mudas_recentes'));
    }
}
