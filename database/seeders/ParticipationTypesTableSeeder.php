<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParticipationTypesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('participation_types')->insert([
            [
                'name' => 'Слушатель',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Выступающий',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
} 