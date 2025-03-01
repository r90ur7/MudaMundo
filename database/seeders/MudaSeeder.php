<?php

namespace Database\Seeders;

use App\Models\Mudas;
use App\Models\User;
use Illuminate\Database\Seeder;

class MudaSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create();

        $mudas = [
            [
                'user_id' => $user->id,
                'tipos_id' => 1,
                'muda_status_id' => 1,
                'especie_id' => 1,
                'nome' => 'Muda de Ipê',
                'descricao' => 'Muda saudável de Ipê com 30cm',
                'quantidade' => 5,
                'cep' => '12345678',
                'logradouro' => 'Rua das Flores',
                'numero' => '123',
                'bairro' => 'Jardim',
                'cidade' => 'São Paulo',
                'uf' => 'SP',
                'donated_at' => null,
                'disabled_at' => null
            ],
            [
                'user_id' => $user->id,
                'tipos_id' => 2,
                'muda_status_id' => 1,
                'especie_id' => 2,
                'nome' => 'Muda de Pau Brasil',
                'descricao' => 'Muda de Pau Brasil com 20cm',
                'quantidade' => 3,
                'cep' => '12345678',
                'logradouro' => 'Rua das Árvores',
                'numero' => '456',
                'bairro' => 'Centro',
                'cidade' => 'São Paulo',
                'uf' => 'SP',
                'donated_at' => null,
                'disabled_at' => null
            ],
        ];

        foreach ($mudas as $muda) {
            Mudas::create($muda);
        }
    }
}
