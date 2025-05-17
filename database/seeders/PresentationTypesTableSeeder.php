<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PresentationTypesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('presentation_types')->insert([
            [
                'name' => 'Онлайн',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Оффлайн',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
} 