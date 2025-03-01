<?php

namespace Database\Seeders;

use App\Models\Especie;
use Illuminate\Database\Seeder;

class EspecieSeeder extends Seeder
{
    public function run(): void
    {
        $especies = [
            [
                'nome' => 'Ipê Amarelo',
                'descricao' => 'Árvore com flores amarelas'
            ],
            [
                'nome' => 'Pau Brasil',
                'descricao' => 'Árvore nativa brasileira'
            ],
            [
                'nome' => 'Orquídea',
                'descricao' => 'Flor ornamental'
            ],
        ];

        foreach ($especies as $especie) {
            Especie::create($especie);
        }
    }
}
