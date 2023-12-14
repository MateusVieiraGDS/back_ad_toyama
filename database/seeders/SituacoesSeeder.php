<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SituacoesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $time = now();
        
        DB::table('situacoes')->insert([
            [
                'nome' => 'Ativo',
                'description' => 'Composto pela membrezia ativa na igreja.',
                'created_at' => $time,
                'updated_at' => $time
            ],
            [
                'nome' => 'Inativo',
                'description' => 'Composto pela membrezia ativa na igreja.',
                'created_at' => $time,
                'updated_at' => $time
            ],
            [
                'nome' => 'Falecido',
                'description' => 'Composto pela membrezia falecida na igreja.',
                'created_at' => $time,
                'updated_at' => $time
            ]
        ]);
    }
}
