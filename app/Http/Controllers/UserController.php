<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Mudas;

class UserController extends Controller
{
    /**
     * Retorna o endereço do usuário logado
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserAddress()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não autenticado'
            ], 401);
        }

        // Verificar se o usuário tem endereço cadastrado
        if (!$user->cep) {
            return response()->json([
                'success' => false,
                'message' => 'Você não tem um endereço cadastrado no seu perfil'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'address' => [
                'cep' => $user->cep,
                'logradouro' => $user->logradouro,
                'numero' => $user->numero,
                'complemento' => $user->complemento,
                'bairro' => $user->bairro,
                'cidade' => $user->cidade,
                'uf' => $user->uf
            ]
        ]);
    }

    /**
     * Retorna o último endereço usado pelo usuário em uma muda
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLastUsedAddress()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não autenticado'
            ], 401);
        }

        // Buscar a última muda cadastrada pelo usuário
        $lastMuda = Mudas::where('user_id', $user->id)
                         ->whereNotNull('cep')
                         ->latest()
                         ->first();

        if (!$lastMuda) {
            return response()->json([
                'success' => false,
                'message' => 'Você ainda não cadastrou nenhuma muda com endereço'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'address' => [
                'cep' => $lastMuda->cep,
                'logradouro' => $lastMuda->logradouro,
                'numero' => $lastMuda->numero,
                'complemento' => $lastMuda->complemento ?? '',
                'bairro' => $lastMuda->bairro,
                'cidade' => $lastMuda->cidade,
                'uf' => $lastMuda->uf
            ]
        ]);
    }
}
