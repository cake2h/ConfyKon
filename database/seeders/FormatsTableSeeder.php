<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormatsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('formats')->insert([
            [
                'name' => 'Онлайн',
                'description' => 'Конференция проводится в онлайн-формате',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Оффлайн',
                'description' => 'Конференция проводится в очном формате',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Гибридная',
                'description' => 'Конференция проводится в смешанном формате',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
} 