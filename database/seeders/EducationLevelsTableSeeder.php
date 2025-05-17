<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EducationLevelsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('education_levels')->insert([
            [
                'name' => 'Высшее',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Бакалавриат',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Магистратура',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Аспирантура',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Среднее профессиональное образование',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Среднее общее образование',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
} 