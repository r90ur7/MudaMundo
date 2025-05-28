<?php
namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Criação dos usuários principais
        $rall = \App\Models\User::firstOrCreate([
            'email' => 'rall@example.com',
        ], [
            'name' => 'rall',
            'password' => bcrypt('Senh@123'),
        ]);
        $thamires = \App\Models\User::firstOrCreate([
            'email' => ' ',
        ], [
            'name' => 'thamires',
            'password' => bcrypt('Senh@123'),
        ]);

        $this->call([
            TipoSeeder::class,
            EspecieSeeder::class,
            MudaStatusSeeder::class,
            SolicitacaoTiposSeeder::class,
            SolicitacaoStatusSeeder::class,
        ]);
        // Chama o seeder de mudas manualmente, passando os usuários
        (new \Database\Seeders\MudaSeeder)->run([$rall, $thamires]);
    }
}
