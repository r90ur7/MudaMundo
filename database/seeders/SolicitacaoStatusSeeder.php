<?php

namespace Database\Seeders;

use App\Models\solicitacao_status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SolicitacaoStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cria os status básicos para solicitações
        $statusList = [
            ['id' => 1, 'nome' => 'Pendente', 'descricao' => 'Solicitação aguardando análise'],
            ['id' => 2, 'nome' => 'Aceita', 'descricao' => 'Solicitação aceita pelo dono da muda'],
            ['id' => 3, 'nome' => 'Rejeitada', 'descricao' => 'Solicitação rejeitada pelo dono da muda'],
            ['id' => 4, 'nome' => 'Cancelada', 'descricao' => 'Solicitação cancelada pelo solicitante'],
            ['id' => 5, 'nome' => 'Concluída', 'descricao' => 'Solicitação concluída com sucesso']
        ];

        foreach ($statusList as $status) {
            solicitacao_status::updateOrCreate(
                ['id' => $status['id']],
                [
                    'nome' => $status['nome'],
                    'descricao' => $status['descricao']
                ]
            );
        }
    }
}
