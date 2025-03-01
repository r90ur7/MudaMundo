<?php

namespace Database\Seeders;

use App\Models\MudaStatus;
use Illuminate\Database\Seeder;

class MudaStatusSeeder extends Seeder
{
    public function run(): void
    {
        $status = [
            [
                'nome' => 'Disponível',
            ],
            [
                'nome' => 'Reservada',
            ],
            [
                'nome' => 'Doada',
            ],
        ];

        foreach ($status as $stats) {
            MudaStatus::create(attributes: $stats);
        }
    }
}