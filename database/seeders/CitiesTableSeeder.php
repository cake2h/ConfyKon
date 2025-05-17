<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesTableSeeder extends Seeder
{
    public function run()
    {
        // Получаем ID России из таблицы countries
        $russiaId = DB::table('countries')
            ->where('name', 'Россия')
            ->value('id');

        if (!$russiaId) {
            echo "Ошибка: Страна 'Россия' не найдена в таблице countries\n";
            return;
        }

        $txtPath = database_path('data/cities.txt');
        if (!file_exists($txtPath)) {
            echo "Файл cities.txt не найден. Положите его в database/data/cities.txt\n";
            return;
        }

        $cities = [];
        foreach (file($txtPath) as $line) {
            $name = trim($line);
            if ($name) {
                $cities[] = [
                    'name' => $name,
                    'country_id' => $russiaId,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        try {
            DB::table('cities')->insert($cities);
            echo "Успешно добавлено " . count($cities) . " городов\n";
        } catch (\Exception $e) {
            echo "Ошибка при добавлении городов: " . $e->getMessage() . "\n";
        }
    }
} 