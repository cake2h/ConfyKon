<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicationStatusesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('application_statuses')->insert([
            [
                'name' => 'Одобрено',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Отклонено',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
} 