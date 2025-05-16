<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            [
                'name' => 'Докладчик',
                'description' => 'Участник, который представляет доклад',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Слушатель',
                'description' => 'Участник, который посещает конференцию без доклада',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Выступающий',
                'description' => 'Участник, который выступает с докладом',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
} 