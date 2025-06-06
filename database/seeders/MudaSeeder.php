<?php

namespace Database\Seeders;

use App\Models\Mudas;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MudaSeeder extends Seeder
{
    public function run($users = null): void
    {
        // Se não receber usuários, pega o primeiro
        if (!$users) {
            $users = [User::first() ?? User::factory()->create()];
        }
        // Alterna entre os usuários para as mudas
        $userCount = count($users);
        $mudas = [
            [
                'user_id' => $users[0]->id,
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
                'foto_url' => 'https://images.unsplash.com/photo-1647550232391-f758832be5c2?q=80&w=2535&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'donated_at' => null,
                'disabled_at' => null
            ],
            [
                'user_id' => $users[1 % $userCount]->id,
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
                'foto_url' => 'https://images.unsplash.com/photo-1501004318641-b39e6451bec6?auto=format&fit=crop&w=800&q=80',
                'donated_at' => null,
                'disabled_at' => null
            ],
            [
                'user_id' => $users[0]->id,
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
                'foto_url' => 'https://images.unsplash.com/photo-1502082553048-f009c37129b9?auto=format&fit=crop&w=800&q=80',
                'donated_at' => null,
                'disabled_at' => null
            ],
            [
                'user_id' => $users[1 % $userCount]->id,
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
                'foto_url' => 'https://images.unsplash.com/photo-1718105314487-aabc56d0b2fc?q=80&w=2535&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'donated_at' => null,
                'disabled_at' => null
            ],
            [
                'user_id' => $users[0]->id,
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
                'foto_url' => 'https://images.unsplash.com/photo-1685728850980-105104d1b0d4?q=80&w=2726&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'donated_at' => null,
                'disabled_at' => null
            ],
            [
                'user_id' => $users[1 % $userCount]->id,
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
                'foto_url' => 'https://images.unsplash.com/photo-1502082553048-f009c37129b9?auto=format&fit=crop&w=800&q=80',
                'donated_at' => null,
                'disabled_at' => null
            ],
            [
                'user_id' => $users[0]->id,
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
                'foto_url' => 'https://images.unsplash.com/photo-1653553003046-adaba7cd6acb?q=80&w=2670&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'donated_at' => null,
                'disabled_at' => null
            ],
            [
                'user_id' => $users[1 % $userCount]->id,
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
                'foto_url' => 'https://images.unsplash.com/photo-1685728850980-105104d1b0d4?q=80&w=2726&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'donated_at' => null,
                'disabled_at' => null
            ],
            [
                'user_id' => $users[0]->id,
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
                'foto_url' => 'https://images.unsplash.com/photo-1672709904990-3cf402fcc29b?q=80&w=2576&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'donated_at' => null,
                'disabled_at' => null
            ],
            [
                'user_id' => $users[1 % $userCount]->id,
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
                'foto_url' => 'https://plus.unsplash.com/premium_photo-1677756430830-5c9d853518a4?q=80&w=2670&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'donated_at' => null,
                'disabled_at' => null
            ],
            [
                'user_id' => $users[0]->id,
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
                'foto_url' => 'https://plus.unsplash.com/premium_photo-1664527009788-29b465a74d51?q=80&w=2574&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'donated_at' => null,
                'disabled_at' => null
            ],
            [
                'user_id' => $users[1 % $userCount]->id,
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
                'foto_url' => 'https://images.unsplash.com/photo-1599199450456-bfe11304e2fd?q=80&w=2670&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'donated_at' => null,
                'disabled_at' => null
            ],
            [
                'user_id' => $users[0]->id,
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
                'foto_url' => 'https://plus.unsplash.com/premium_photo-1665669278389-4190e7af7e54?q=80&w=2670&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'donated_at' => null,
                'disabled_at' => null
            ],
            [
                'user_id' => $users[1 % $userCount]->id,
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
                'foto_url' => 'https://images.unsplash.com/photo-1718105314487-aabc56d0b2fc?q=80&w=2535&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'donated_at' => null,
                'disabled_at' => null
            ],
            [
                'user_id' => $users[0]->id,
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
                'foto_url' => 'https://plus.unsplash.com/premium_photo-1665669278389-4190e7af7e54?q=80&w=2670&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'donated_at' => null,
                'disabled_at' => null
            ],
        ];
        // Adiciona as demais mudas, alternando entre os usuários e usando imagens reais
        $imagens = [
            'https://images.unsplash.com/photo-1647550232391-f758832be5c2?q=80&w=2535&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1501004318641-b39e6451bec6?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1502082553048-f009c37129b9?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1718105314487-aabc56d0b2fc?q=80&w=2535&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1663952329807-15901df90001?q=80&w=2252&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1599199450456-bfe11304e2fd?q=80&w=2670&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1685728850980-105104d1b0d4?q=80&w=2726&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://plus.unsplash.com/premium_photo-1664527009788-29b465a74d51?q=80&w=2574&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1653553003046-adaba7cd6acb?q=80&w=2670&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://plus.unsplash.com/premium_photo-1677756430830-5c9d853518a4?q=80&w=2670&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1502082553048-f009c37129b9?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1672709904990-3cf402fcc29b?q=80&w=2576&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://plus.unsplash.com/premium_photo-1665669278389-4190e7af7e54?q=80&w=2670&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1502082553048-f009c37129b9?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1501004318641-b39e6451bec6?auto=format&fit=crop&w=800&q=80',
        ];
        // Preenche as mudas restantes
        for ($i = 4; $i < 15; $i++) {
            $muda = [
                ...$mudas[$i],
                'user_id' => $users[$i % $userCount]->id,
                'foto_url' => $imagens[$i % count($imagens)]
            ];
            $mudas[$i] = $muda;
        }
        // Função para baixar e salvar a imagem no storage igual ao upload manual
        function baixarImagemParaStorage($url) {
            $nomeArquivo = 'muda_' . Str::random(12) . '.jpg';
            $caminho = 'mudas/' . $nomeArquivo;
            try {
                $conteudo = @file_get_contents($url);
                if ($conteudo !== false) {
                    Storage::disk('public')->put($caminho, $conteudo);
                    return $nomeArquivo;
                }
            } catch (\Exception $e) {}
            return null;
        }
        // Para cada muda, baixar a imagem e salvar só o nome do arquivo
        foreach ($mudas as &$muda) {
            if (!empty($muda['foto_url']) && Str::startsWith($muda['foto_url'], 'http')) {
                $nomeArquivo = baixarImagemParaStorage($muda['foto_url']);
                if ($nomeArquivo) {
                    $muda['foto_url'] = $nomeArquivo;
                } else {
                    $muda['foto_url'] = null;
                }
            }
        }
        unset($muda);
        foreach ($mudas as $muda) {
            Mudas::create($muda);
        }
    }
}
