<?php

namespace Database\Seeders;

use App\Models\Tipo;
use Illuminate\Database\Seeder;

class TipoSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            [
                'nome' => 'Árvore',
                'nome_cientifico' => 'Arborus',
                'descricao' => 'Plantas de grande porte'
            ],
            [
                'nome' => 'Arbusto',
                'nome_cientifico' => 'Shrubbus',
                'descricao' => 'Plantas de médio porte'
            ],
            [
                'nome' => 'Flor',
                'nome_cientifico' => 'Florus',
                'descricao' => 'Plantas ornamentais'
            ],
        ];

        foreach ($tipos as $tipo) {
            Tipo::create($tipo);
        }
    }
}