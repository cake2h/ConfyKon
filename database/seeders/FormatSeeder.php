<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Format;

class FormatSeeder extends Seeder
{
    public function run()
    {
        $formats = [
            [
                'name' => 'Очная',
                'description' => 'Конференция проводится в очном формате с физическим присутствием участников'
            ],
            [
                'name' => 'Онлайн',
                'description' => 'Конференция проводится в онлайн-формате через видеоконференции'
            ],
            [
                'name' => 'Гибридная',
                'description' => 'Конференция проводится в смешанном формате: часть участников присутствует очно, часть - онлайн'
            ],
            [
                'name' => 'Заочная',
                'description' => 'Конференция проводится в заочном формате без физического присутствия участников'
            ]
        ];

        foreach ($formats as $format) {
            Format::create($format);
        }
    }
} 