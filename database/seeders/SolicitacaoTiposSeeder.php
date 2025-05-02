<?php

namespace Database\Seeders;

use App\Models\solicitacao_tipos;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SolicitacaoTiposSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cria os tipos básicos de solicitações
        $tipos = [
            ['id' => 1, 'nome' => 'Doação', 'descricao' => 'Solicitação de doação de muda'],
            ['id' => 2, 'nome' => 'Permuta', 'descricao' => 'Solicitação de troca de muda'],
        ];

        foreach ($tipos as $tipo) {
            solicitacao_tipos::updateOrCreate(
                ['id' => $tipo['id']],
                [
                    'nome' => $tipo['nome'],
                    'descricao' => $tipo['descricao']
                ]
            );
        }
    }
}
