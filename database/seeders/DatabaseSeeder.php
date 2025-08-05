<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CountriesTableSeeder::class,
            CitiesTableSeeder::class,
            StudyPlacesTableSeeder::class,
            FormatSeeder::class,
            EducationLevelsTableSeeder::class,
            ParticipationTypesTableSeeder::class,
            FormatsTableSeeder::class,
            PresentationTypesTableSeeder::class,
            ApplicationStatusesTableSeeder::class,
            ReportStatusesTableSeeder::class,
            TestDataSeeder::class,
            PaymentsTableSeeder::class,
        ]);
        $this->call(QuestionThemeSeeder::class);
    }
}
