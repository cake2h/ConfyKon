<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('countries')->insert([
            [
                'name' => 'Россия',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
} 