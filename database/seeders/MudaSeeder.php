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
            [
                'user_id' => $user->id,
                'tipos_id' => 3,
                'muda_status_id' => 1,
                'especie_id' => 3,
                'nome' => 'Orquídea Brasileira',
                'descricao' => 'Linda orquídea nativa',
                'quantidade' => 2,
                'cep' => '12345678',
                'logradouro' => 'Rua das Orquídeas',
                'numero' => '789',
                'bairro' => 'Vila Flora',
                'cidade' => 'São Paulo',
                'uf' => 'SP',
                'donated_at' => null,
                'disabled_at' => null
            ],
            [
                'user_id' => $user->id,
                'tipos_id' => 1,
                'muda_status_id' => 1,
                'especie_id' => 1,
                'nome' => 'Ipê Amarelo',
                'descricao' => 'Muda de Ipê Amarelo 40cm',
                'quantidade' => 4,
                'cep' => '12345678',
                'logradouro' => 'Alameda Santos',
                'numero' => '1000',
                'bairro' => 'Jardim Paulista',
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
                'nome' => 'Pau Brasil Jovem',
                'descricao' => 'Muda jovem de Pau Brasil',
                'quantidade' => 3,
                'cep' => '12345678',
                'logradouro' => 'Rua Augusta',
                'numero' => '2000',
                'bairro' => 'Consolação',
                'cidade' => 'São Paulo',
                'uf' => 'SP',
                'donated_at' => null,
                'disabled_at' => null
            ],
            [
                'user_id' => $user->id,
                'tipos_id' => 3,
                'muda_status_id' => 1,
                'especie_id' => 3,
                'nome' => 'Orquídea Roxa',
                'descricao' => 'Orquídea com flores roxas',
                'quantidade' => 2,
                'cep' => '12345678',
                'logradouro' => 'Rua Oscar Freire',
                'numero' => '3000',
                'bairro' => 'Jardins',
                'cidade' => 'São Paulo',
                'uf' => 'SP',
                'donated_at' => null,
                'disabled_at' => null
            ],
            [
                'user_id' => $user->id,
                'tipos_id' => 1,
                'muda_status_id' => 1,
                'especie_id' => 1,
                'nome' => 'Ipê Rosa',
                'descricao' => 'Muda de Ipê Rosa 35cm',
                'quantidade' => 6,
                'cep' => '12345678',
                'logradouro' => 'Avenida Paulista',
                'numero' => '4000',
                'bairro' => 'Bela Vista',
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
                'nome' => 'Pau Brasil Adulto',
                'descricao' => 'Muda desenvolvida de Pau Brasil',
                'quantidade' => 2,
                'cep' => '12345678',
                'logradouro' => 'Rua Consolação',
                'numero' => '5000',
                'bairro' => 'Consolação',
                'cidade' => 'São Paulo',
                'uf' => 'SP',
                'donated_at' => null,
                'disabled_at' => null
            ],
            [
                'user_id' => $user->id,
                'tipos_id' => 3,
                'muda_status_id' => 1,
                'especie_id' => 3,
                'nome' => 'Orquídea Branca',
                'descricao' => 'Orquídea com flores brancas',
                'quantidade' => 3,
                'cep' => '12345678',
                'logradouro' => 'Rua Estados Unidos',
                'numero' => '6000',
                'bairro' => 'Jardim América',
                'cidade' => 'São Paulo',
                'uf' => 'SP',
                'donated_at' => null,
                'disabled_at' => null
            ],
            [
                'user_id' => $user->id,
                'tipos_id' => 1,
                'muda_status_id' => 1,
                'especie_id' => 1,
                'nome' => 'Ipê Branco',
                'descricao' => 'Muda de Ipê Branco 45cm',
                'quantidade' => 4,
                'cep' => '12345678',
                'logradouro' => 'Rua Haddock Lobo',
                'numero' => '7000',
                'bairro' => 'Cerqueira César',
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
                'nome' => 'Pau Brasil Pequeno',
                'descricao' => 'Muda pequena de Pau Brasil',
                'quantidade' => 5,
                'cep' => '12345678',
                'logradouro' => 'Alameda Jaú',
                'numero' => '8000',
                'bairro' => 'Jardim Paulista',
                'cidade' => 'São Paulo',
                'uf' => 'SP',
                'donated_at' => null,
                'disabled_at' => null
            ],
            [
                'user_id' => $user->id,
                'tipos_id' => 3,
                'muda_status_id' => 1,
                'especie_id' => 3,
                'nome' => 'Orquídea Amarela',
                'descricao' => 'Orquídea com flores amarelas',
                'quantidade' => 2,
                'cep' => '12345678',
                'logradouro' => 'Rua Pamplona',
                'numero' => '9000',
                'bairro' => 'Jardim Paulista',
                'cidade' => 'São Paulo',
                'uf' => 'SP',
                'donated_at' => null,
                'disabled_at' => null
            ],
            [
                'user_id' => $user->id,
                'tipos_id' => 1,
                'muda_status_id' => 1,
                'especie_id' => 1,
                'nome' => 'Ipê Roxo',
                'descricao' => 'Muda de Ipê Roxo 50cm',
                'quantidade' => 3,
                'cep' => '12345678',
                'logradouro' => 'Rua Bela Cintra',
                'numero' => '10000',
                'bairro' => 'Consolação',
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
                'nome' => 'Pau Brasil Grande',
                'descricao' => 'Muda grande de Pau Brasil',
                'quantidade' => 2,
                'cep' => '12345678',
                'logradouro' => 'Alameda Lorena',
                'numero' => '11000',
                'bairro' => 'Jardins',
                'cidade' => 'São Paulo',
                'uf' => 'SP',
                'donated_at' => null,
                'disabled_at' => null
            ],
            [
                'user_id' => $user->id,
                'tipos_id' => 3,
                'muda_status_id' => 1,
                'especie_id' => 3,
                'nome' => 'Orquídea Rosa',
                'descricao' => 'Orquídea com flores rosa',
                'quantidade' => 4,
                'cep' => '12345678',
                'logradouro' => 'Rua da Consolação',
                'numero' => '12000',
                'bairro' => 'Consolação',
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
