<?php

namespace App\Exports;

use App\Models\Application;
use App\Models\Section;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;

class UsersExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    use Exportable;

    protected $presentationCounts;
    protected $nonLocalParticipants;
    protected $sections;

    public function __construct($presentationCounts, $nonLocalParticipants, $sections)
    {
        $this->presentationCounts = $presentationCounts;
        $this->nonLocalParticipants = $nonLocalParticipants;
        $this->sections = $sections;
    }

    public function collection()
    {
        // Получаем данные о пользователях
        $users = User::select('name', 'birthday', 'email', 'city', 'study_place')
            ->get();

        // Формируем данные для статистики
        $data[] = [' '];
        $data[] = ['Имя', 'Дата рождения', 'Email', 'Город', 'Место учебы'];

        foreach ($users as $user) {
            $data[] = [
                $user->name,
                $user->birthday,
                $user->email,
                $user->city,
                $user->study_place,
            ];
        }

        // Добавляем разделитель для статистики
        $data[] = [' '];
        $data[] = ['Тип выступления', 'Количество'];
        foreach ($this->presentationCounts as $count) {
            $data[] = [$count->type, $count->count];
        }

        // Добавляем информацию об иногородних участниках
        $data[] = [' '];
        $data[] = ['Иногородние участники', $this->nonLocalParticipants];

        // Добавляем информацию о количестве пользователей на каждой секции
        $data[] = [' '];
        $data[] = ['Секция', 'Количество участников'];
        foreach ($this->sections as $section) {
            $data[] = [$section->name, $section->users_count];
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [];
    }
}



