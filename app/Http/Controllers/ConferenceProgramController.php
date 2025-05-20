<?php

namespace App\Http\Controllers;

use App\Models\Section;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ConferenceProgramController extends Controller
{
    public function index($conferenceId)
    {
        $sections = Section::with(['applications.user', 'applications.report', 'moder'])
            ->where('conference_id', $conferenceId)
            ->orderBy('date_start')
            ->get()
            ->map(function ($section) {
                $section->applications = $section->applications->sortBy(function ($application) {
                    return $application->user->last_name;
                });
                return $section;
            });

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Устанавливаем заголовки
        $sheet->setCellValue('A1', 'Программа конференции');
        $sheet->mergeCells('A1:D1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Заголовки колонок
        $sheet->setCellValue('A3', 'Секция');
        $sheet->setCellValue('B3', 'Модератор');
        $sheet->setCellValue('C3', 'Время проведения');
        $sheet->setCellValue('D3', 'Участники и темы докладов');
        
        // Стили для заголовков
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E0E0E0'],
            ],
        ];
        $sheet->getStyle('A3:D3')->applyFromArray($headerStyle);

        $row = 4;
        foreach ($sections as $section) {
            // Стили для секции, модератора и времени (жирный шрифт и центрирование)
            $boldCenterStyle = [
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_TOP
                ]
            ];
            
            $sheet->setCellValue('A' . $row, $section->name);
            $sheet->setCellValue('B' . $row, $section->moder->surname . ' ' . $section->moder->name . ' ' . $section->moder->patronymic);
            $sheet->setCellValue('C' . $row, \Carbon\Carbon::parse($section->date_start)->format('d.m.Y H:i') . ' - ' . 
                \Carbon\Carbon::parse($section->date_end)->format('H:i'));
            
            // Применяем стили к ячейкам A, B и C
            $sheet->getStyle('A' . $row)->applyFromArray($boldCenterStyle);
            $sheet->getStyle('B' . $row)->applyFromArray($boldCenterStyle);
            $sheet->getStyle('C' . $row)->applyFromArray($boldCenterStyle);

            $participants = [];
            foreach ($section->applications as $application) {
                // Получаем тип выступления
                $participationType = $application->participationType->name ?? '';
                
                // Формируем текст с курсивом для ФИО
                $fullName = $application->user->surname . ' ' . $application->user->name . ' ' . 
                    $application->user->patronymic;
                
                // Создаем RichText объект для каждой строки
                $richText = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
                
                // Добавляем ФИО курсивом
                $nameRun = $richText->createTextRun($fullName);
                $nameRun->getFont()->setItalic(true);
                
                // Добавляем остальной текст обычным шрифтом
                $richText->createTextRun(' - ' . $application->report->report_theme . ' (' . $participationType . ')');
                
                $participants[] = $richText;
            }
            
            // Устанавливаем первую строку
            if (!empty($participants)) {
                $sheet->getCell('D' . $row)->setValue($participants[0]);
                
                // Добавляем остальные строки
                for ($i = 1; $i < count($participants); $i++) {
                    $currentValue = $sheet->getCell('D' . $row)->getValue();
                    $newValue = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
                    $newValue->createTextRun($currentValue->getPlainText() . "\n");
                    $newValue->createTextRun($participants[$i]->getPlainText());
                    $sheet->getCell('D' . $row)->setValue($newValue);
                }
            }
            
            // Стили для ячеек
            $sheet->getStyle('A' . $row . ':D' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle('D' . $row)->getAlignment()->setWrapText(true);
            
            $row++;
        }

        // Автоматическая ширина колонок
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Создаем файл
        $writer = new Xlsx($spreadsheet);
        $filename = 'program_' . date('Y-m-d_H-i-s') . '.xlsx';
        $path = storage_path('app/public/' . $filename);
        $writer->save($path);

        return response()->download($path, $filename)->deleteFileAfterSend(true);
    }
} 