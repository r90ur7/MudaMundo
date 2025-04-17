<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        $tipos = \App\Models\Tipo::orderBy('nome')->get();
        $estados = ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'];

        $query = \App\Models\Mudas::query()
            ->with(['tipo', 'status'])
            ->where('user_id', $user->id)
            ->whereNull('disabled_at');

        if ($request->filled('tipo')) {
            $query->where('tipos_id', $request->tipo);
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

        $mudas = $query->latest()->paginate(12)->appends($request->query());

        return view('profile.edit', [
            'user' => $user,
            'mudas' => $mudas,
            'tipos' => $tipos,
            'estados' => $estados,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Retorna os cards das mudas do usuário autenticado, filtrados com AJAX.
     */
    public function minhasMudas(Request $request)
    {
        $user = $request->user();
        $tipos = \App\Models\Tipo::orderBy('nome')->get();
        $estados = ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'];

        $filterType = $request->get('filter_type', 'all');

        if ($filterType === 'favoritas') {
            // Se o filtro for 'favoritas', busca as mudas que o usuário favoritou
            $query = \App\Models\Mudas::with(['tipo', 'status'])
                ->whereHas('favoritos', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->whereNull('disabled_at');
        } elseif ($filterType === 'cadastradas') {
            $query = \App\Models\Mudas::with(['tipo', 'status'])
                ->where('user_id', $user->id)
                ->whereNull('disabled_at');
        } elseif ($filterType === 'doadas') {
            $query = \App\Models\Mudas::with(['tipo', 'status'])
                ->where('user_id', $user->id)
                ->where('status_id', 3)
                ->whereNull('disabled_at');
        } else {
            $query = \App\Models\Mudas::with(['tipo', 'status'])
                ->where('user_id', $user->id)
                ->whereNull('disabled_at');
        }
        if ($request->filled('tipo')) {
            $query->where('tipos_id', $request->tipo);
        }
        if ($request->filled('location')) {
            $query->where('uf', $request->location);
        }
        // Se o campo de pesquisa estiver preenchido, aplicar filtro de pesquisa
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                ->orWhere('descricao', 'like', "%{$search}%");
            });
        }

        $mudas = $query->latest()->paginate(12)->appends($request->query());

        // Retorna apenas o HTML dos cards
        return response()->json([
            'html' => view('mudas.partials.cards', compact('mudas'))->render()
        ]);
    }
}
