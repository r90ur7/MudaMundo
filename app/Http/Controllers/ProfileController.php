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

        // Histórico de atividades
        // Mudas cadastradas pelo usuário
        $mudasCadastradas = \App\Models\Mudas::where('user_id', $user->id)
            ->whereNull('donated_at')
            ->whereNull('disabled_at')
            ->latest()
            ->take(10)
            ->get();
        // Mudas doadas pelo usuário
        $mudasDoadas = \App\Models\Mudas::where('user_id', $user->id)
            ->whereNotNull('donated_at')
            ->latest('donated_at')
            ->take(10)
            ->get();
        // Solicitações enviadas
        $solicitacoesEnviadas = \App\Models\Solicitacao::with(['mudas', 'status', 'tipo'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();
        // Solicitações recebidas (nas mudas do usuário)
        $solicitacoesRecebidas = \App\Models\Solicitacao::with(['mudas', 'status', 'tipo', 'user'])
            ->whereHas('mudas', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest()
            ->take(10)
            ->get();
        // Solicitações rejeitadas (enviadas ou recebidas)
        $solicitacoesRejeitadas = \App\Models\Solicitacao::with(['mudas', 'status', 'tipo', 'user'])
            ->where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereHas('mudas', function($q2) use ($user) {
                      $q2->where('user_id', $user->id);
                  });
            })
            ->whereHas('status', function($q) {
                $q->where('nome', 'Rejeitada');
            })
            ->latest('rejected_at')
            ->take(10)
            ->get();

        // Filtro de tipo de exibição (favoritas, cadastradas, transferidas, desabilitadas)
        $filterType = $request->get('filter_type', 'all');

        $query = \App\Models\Mudas::with(['tipo', 'status']);
        switch ($filterType) {
            case 'favoritas':
                $query->whereHas('favoritos', fn($q) => $q->where('user_id', $user->id))
                      ->whereNull('disabled_at');
                break;
            case 'cadastradas':
                $query->where('user_id', $user->id)
                      ->whereNull('donated_at')
                      ->whereNull('disabled_at');
                break;
            case 'transferidas':
                $query->whereNotNull('donated_at')
                      ->where(fn($q) => $q->where('user_id', $user->id)
                                             ->orWhere('donated_to', $user->id));
                break;
            case 'desabilitadas':
                $query->whereNotNull('disabled_at')
                      ->where('user_id', $user->id);
                break;
            default: // all
                $query->whereNull('disabled_at')
                      ->where(fn($q) =>
                          $q->where(fn($q2) =>
                              $q2->where('user_id', $user->id)
                                  ->whereNull('donated_at')
                          )
                          ->orWhere('donated_to', $user->id)
                      );
        }

        // Aplicar filtros adicionais de busca, tipo, localização
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
            // Histórico
            'mudasCadastradas' => $mudasCadastradas,
            'mudasDoadas' => $mudasDoadas,
            'solicitacoesEnviadas' => $solicitacoesEnviadas,
            'solicitacoesRecebidas' => $solicitacoesRecebidas,
            'solicitacoesRejeitadas' => $solicitacoesRejeitadas,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $user = $request->user();

        if ($request->hasFile('foto')) {
            $path = $this->processImageUpload($request->file('foto'));
            if ($path) {
                $validated['foto_url'] = $path;
            }
        }
        unset($validated['foto']);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Atualiza apenas a foto de perfil do usuário.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePhoto(Request $request): RedirectResponse
    {
        // Validação do upload
        try {
            $request->validate([
                'foto' => ['required', 'image', 'max:51200'], // 50MB
            ], [
                'foto.required' => 'Por favor, selecione uma imagem para enviar.',
                'foto.image' => 'O arquivo selecionado não é uma imagem válida.',
                'foto.max' => 'A imagem não pode ultrapassar 50 MB.',
                'foto.uploaded' => 'Falha ao enviar a imagem. Verifique sua conexão e tente novamente.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator->errors()->getMessages());
        }

        $user = $request->user();
        $file = $request->file('foto');
        if (! $file->isValid()) {
            $code = $file->getError();
            $msg = match ($code) {
                UPLOAD_ERR_INI_SIZE   => 'O arquivo excede o tamanho máximo permitido pelo servidor.',
                UPLOAD_ERR_FORM_SIZE  => 'O arquivo excede o tamanho máximo definido no formulário.',
                UPLOAD_ERR_PARTIAL    => 'O upload foi parcial. Tente novamente.',
                UPLOAD_ERR_NO_FILE    => 'Nenhum arquivo foi enviado.',
                UPLOAD_ERR_NO_TMP_DIR => 'Pasta temporária ausente.',
                UPLOAD_ERR_CANT_WRITE => 'Falha ao gravar o arquivo em disco.',
                UPLOAD_ERR_EXTENSION  => 'Upload interrompido por extensão.',
                default               => 'Erro desconhecido no upload de arquivo.',
            };
            return back()->withErrors(['foto' => $msg]);
        }

        try {
            $path = $this->processImageUpload($file);
            if ($path) {
                $user->foto_url = $path;
                $user->save();
                return redirect()->route('profile.edit')->with('status', 'profile-photo-updated');
            }
            return back()->withErrors(['foto' => 'Não foi possível salvar a imagem. Tente novamente.']);
        } catch (\Exception $e) {
            return back()->withErrors(['foto' => 'Erro ao processar imagem: ' . $e->getMessage()]);
        }
    }

    /**
     * Processa o upload de uma imagem de perfil e retorna o caminho relativo.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return string|null
     */
    private function processImageUpload($file)
    {
        if ($file && $file->isValid()) {
            try {
                // Salva a imagem no diretório storage/app/public/profile
                $newFilename = 'profile_' . auth()->id() . '_' . time() . '.' . $file->getClientOriginalExtension();

                return $file->storeAs('profile', $newFilename, 'public');

            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Erro ao processar imagem de perfil: ' . $e->getMessage());
                return null;
            }
        }

        return null;
    }

    /**
     * Exibe a foto de perfil do usuário.
     */
    public function getPhoto(Request $request)
    {
        $filename = $request->query('filename');
        $path = storage_path('app/public/' . $filename);
        if (!file_exists($path)) {
            abort(404);
        }
        $content = file_get_contents($path);
        $mime = mime_content_type($path);
        return response($content, 200)->header('Content-Type', $mime);
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
        $query = \App\Models\Mudas::with(['tipo', 'status']);
        switch ($filterType) {
            case 'favoritas':
                $query->whereHas('favoritos', fn($q) => $q->where('user_id', $user->id))
                      ->whereNull('disabled_at');
                break;
            case 'cadastradas':
                $query->where('user_id', $user->id)
                      ->whereNull('donated_at')
                      ->whereNull('disabled_at');
                break;
            case 'transferidas':
                $query->whereNotNull('donated_at')
                      ->where(fn($q) =>
                          $q->where('user_id', $user->id)
                            ->orWhere('donated_to', $user->id)
                      );
                break;
            case 'desabilitadas':
                $query->whereNotNull('disabled_at')
                      ->where('user_id', $user->id);
                break;
            default: // all
                $query->whereNull('disabled_at')
                      ->where(fn($q) =>
                          $q->where(fn($q2) =>
                              $q2->where('user_id', $user->id)
                                 ->whereNull('donated_at')
                          )
                          ->orWhere('donated_to', $user->id)
                      );
        }
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
        return response()->json([
            'html' => view('mudas.partials.cards', compact('mudas'))->render()
        ]);
    }
}
