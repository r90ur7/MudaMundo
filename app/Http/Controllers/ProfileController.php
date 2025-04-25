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

        if ($filterType === 'favoritas') {
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
