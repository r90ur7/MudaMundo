<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'descricao',
        'foto_url',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'uf',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Acessor para obter a URL da foto de perfil formatada corretamente
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        if (!$this->foto_url) {
            return null;
        }

        // Garantir que a URL comece com "profile/" e não "public/profile/"
        $path = $this->foto_url;

        // Se o caminho já começa com "public/", remova para evitar duplicação
        if (strpos($path, 'public/') === 0) {
            $path = substr($path, 7); // Remove "public/"
        }

        // Logar o que está sendo retornado
        \Illuminate\Support\Facades\Log::info('URL da foto de perfil: storage/' . $path);

        return $path;
    }

    public function favorites()
    {
        return $this->belongsToMany(Mudas::class, 'favoritos', 'user_id', 'muda_id')
                    ->withTimestamps();
    }
}
