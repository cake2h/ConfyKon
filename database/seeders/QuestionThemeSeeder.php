<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionThemeSeeder extends Seeder
{
    public function run()
    {
        DB::table('question_themes')->insert([
            [
                'name' => 'Общее',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'О Конференции',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
} 