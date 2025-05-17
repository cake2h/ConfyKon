<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportStatusesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('report_statuses')->insert([
            [
                'name' => 'На рассмотрении',
                'created_at' => now(),
                'updated_at' => now(),
            ],
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