<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // Отключаем проверки внешних ключей
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('applications')->truncate();
        DB::table('reports')->truncate();
        DB::table('sections')->truncate();
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Получаем id справочников
        $educationLevelId = DB::table('education_levels')->value('id');
        $cityId = DB::table('cities')->value('id');
        $studyPlaceId = DB::table('study_places')->value('id');
        $presentationTypeId = DB::table('presentation_types')->value('id');
        $participationTypeId = DB::table('participation_types')->value('id');
        $applicationStatusId = DB::table('application_statuses')->value('id');
        $reportStatusId = DB::table('report_statuses')->value('id');

        // Добавляем модераторов
        $moderators = [
            ['surname' => 'Петров', 'name' => 'Александр', 'patronymic' => 'Иванович', 'email' => 'petrov@example.com'],
            ['surname' => 'Сидорова', 'name' => 'Мария', 'patronymic' => 'Александровна', 'email' => 'sidorova@example.com'],
            ['surname' => 'Козлов', 'name' => 'Дмитрий', 'patronymic' => 'Сергеевич', 'email' => 'kozlov@example.com'],
            ['surname' => 'Новикова', 'name' => 'Елена', 'patronymic' => 'Дмитриевна', 'email' => 'novikova@example.com'],
            ['surname' => 'Смирнов', 'name' => 'Иван', 'patronymic' => 'Петрович', 'email' => 'smirnov@example.com'],
        ];
        $moderatorIds = [];
        foreach ($moderators as $moderator) {
            $moderatorIds[] = DB::table('users')->insertGetId([
                'surname' => $moderator['surname'],
                'name' => $moderator['name'],
                'patronymic' => $moderator['patronymic'],
                'birthday' => '1980-01-01',
                'email' => $moderator['email'],
                'password' => bcrypt('password'),
                'phone_number' => '+79990000000',
                'consent_to_mailing' => true,
                'education_level_id' => $educationLevelId,
                'city_id' => $cityId,
                'study_place_id' => $studyPlaceId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Добавляем секции
        $sectionsData = [
            ['name' => 'ИИ и машинное обучение', 'date_start' => '2024-06-15 10:00:00', 'date_end' => '2024-06-15 13:00:00'],
            ['name' => 'Кибербезопасность', 'date_start' => '2024-06-15 14:00:00', 'date_end' => '2024-06-15 17:00:00'],
            ['name' => 'Веб-разработка', 'date_start' => '2024-06-16 10:00:00', 'date_end' => '2024-06-16 13:00:00'],
            ['name' => 'Мобильная разработка', 'date_start' => '2024-06-16 14:00:00', 'date_end' => '2024-06-16 17:00:00'],
            ['name' => 'Большие данные', 'date_start' => '2024-06-17 10:00:00', 'date_end' => '2024-06-17 13:00:00'],
        ];
        $sectionIds = [];
        foreach ($sectionsData as $i => $section) {
            $sectionIds[] = DB::table('sections')->insertGetId([
                'name' => $section['name'],
                'description' => null,
                'date_start' => $section['date_start'],
                'date_end' => $section['date_end'],
                'conference_id' => 1,
                'user_id' => $moderatorIds[$i],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Данные участников и докладов
        $participants = [
            [
                ['surname' => 'Кузнецова', 'name' => 'Анна', 'patronymic' => 'Александровна'],
                ['surname' => 'Иванов', 'name' => 'Сергей', 'patronymic' => 'Петрович'],
                ['surname' => 'Соколова', 'name' => 'Ольга', 'patronymic' => 'Дмитриевна'],
                ['surname' => 'Морозов', 'name' => 'Алексей', 'patronymic' => 'Сергеевич'],
                ['surname' => 'Волкова', 'name' => 'Екатерина', 'patronymic' => 'Ивановна'],
            ],
            [
                ['surname' => 'Лебедев', 'name' => 'Дмитрий', 'patronymic' => 'Александрович'],
                ['surname' => 'Козлова', 'name' => 'Наталья', 'patronymic' => 'Сергеевна'],
                ['surname' => 'Новиков', 'name' => 'Андрей', 'patronymic' => 'Иванович'],
                ['surname' => 'Морозова', 'name' => 'Марина', 'patronymic' => 'Петровна'],
                ['surname' => 'Соколов', 'name' => 'Игорь', 'patronymic' => 'Дмитриевич'],
            ],
            [
                ['surname' => 'Иванова', 'name' => 'Татьяна', 'patronymic' => 'Александровна'],
                ['surname' => 'Смирнов', 'name' => 'Павел', 'patronymic' => 'Сергеевич'],
                ['surname' => 'Кузнецова', 'name' => 'Юлия', 'patronymic' => 'Петровна'],
                ['surname' => 'Волков', 'name' => 'Александр', 'patronymic' => 'Иванович'],
                ['surname' => 'Лебедева', 'name' => 'Елена', 'patronymic' => 'Дмитриевна'],
            ],
            [
                ['surname' => 'Новиков', 'name' => 'Михаил', 'patronymic' => 'Александрович'],
                ['surname' => 'Соколова', 'name' => 'Анна', 'patronymic' => 'Сергеевна'],
                ['surname' => 'Морозов', 'name' => 'Сергей', 'patronymic' => 'Петрович'],
                ['surname' => 'Козлова', 'name' => 'Ольга', 'patronymic' => 'Ивановна'],
                ['surname' => 'Иванов', 'name' => 'Дмитрий', 'patronymic' => 'Дмитриевич'],
            ],
            [
                ['surname' => 'Смирнова', 'name' => 'Екатерина', 'patronymic' => 'Александровна'],
                ['surname' => 'Волков', 'name' => 'Алексей', 'patronymic' => 'Сергеевич'],
                ['surname' => 'Лебедева', 'name' => 'Наталья', 'patronymic' => 'Петровна'],
                ['surname' => 'Кузнецов', 'name' => 'Игорь', 'patronymic' => 'Иванович'],
                ['surname' => 'Новикова', 'name' => 'Марина', 'patronymic' => 'Дмитриевна'],
            ],
        ];
        $reports = [
            [
                'Нейронные сети в обработке естественного языка',
                'Применение машинного обучения в медицинской диагностике',
                'Этические аспекты искусственного интеллекта',
                'Глубокое обучение для компьютерного зрения',
                'Рекомендательные системы на основе ИИ',
            ],
            [
                'Современные методы защиты от фишинга',
                'Блокчейн и криптография',
                'Безопасность IoT-устройств',
                'Анализ уязвимостей веб-приложений',
                'Защита персональных данных в облачных сервисах',
            ],
            [
                'Микросервисная архитектура в современных веб-приложениях',
                'Оптимизация производительности фронтенда',
                'Serverless архитектура: преимущества и недостатки',
                'GraphQL vs REST API',
                'Progressive Web Apps: будущее веб-разработки',
            ],
            [
                'Кроссплатформенная разработка на Flutter',
                'UI/UX тренды в мобильных приложениях',
                'Оптимизация производительности Android-приложений',
                'SwiftUI: современный подход к iOS-разработке',
                'Тестирование мобильных приложений',
            ],
            [
                'Обработка и анализ больших данных с помощью Apache Spark',
                'Визуализация данных в реальном времени',
                'Машинное обучение на больших данных',
                'Хранилища данных: современные решения',
                'Аналитика в социальных сетях',
            ],
        ];

        foreach ($sectionIds as $sectionIndex => $sectionId) {
            foreach ($participants[$sectionIndex] as $i => $person) {
                $userId = DB::table('users')->insertGetId([
                    'surname' => $person['surname'],
                    'name' => $person['name'],
                    'patronymic' => $person['patronymic'],
                    'birthday' => '2000-01-01',
                    'email' => strtolower($person['surname']) . $sectionIndex . '@example.com',
                    'password' => bcrypt('password'),
                    'phone_number' => '+79990000001',
                    'consent_to_mailing' => true,
                    'education_level_id' => $educationLevelId,
                    'city_id' => $cityId,
                    'study_place_id' => $studyPlaceId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $reportId = DB::table('reports')->insertGetId([
                    'report_theme' => $reports[$sectionIndex][$i],
                    'file_path' => null,
                    'report_status_id' => $reportStatusId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                DB::table('applications')->insert([
                    'section_id' => $sectionId,
                    'user_id' => $userId,
                    'presentation_type_id' => $presentationTypeId,
                    'report_id' => $reportId,
                    'participation_type_id' => $participationTypeId,
                    'application_status_id' => $applicationStatusId,
                    'contributors' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
} 