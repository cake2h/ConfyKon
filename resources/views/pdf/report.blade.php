<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Отчет по конференции</title>
    <style>
        @page {
            margin: 2cm;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            page-break-after: avoid;
        }
        .header h1 {
            color: #333;
            font-size: 18px;
            margin: 0 0 10px 0;
            padding: 0;
        }
        .header p {
            color: #666;
            font-size: 12px;
            margin: 3px 0;
            padding: 0;
        }
        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .section h2 {
            color: #333;
            font-size: 14px;
            border-bottom: 1px solid #333;
            padding-bottom: 3px;
            margin: 0 0 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px 6px;
            text-align: left;
            font-size: 11px;
            vertical-align: top;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .stats-container {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .stats-box {
            width: 100%;
            border: 1px solid #000;
            padding: 8px;
            margin-bottom: 15px;
        }
        .stats-box h3 {
            margin: 0 0 8px 0;
            padding: 0;
            color: #333;
            font-size: 13px;
        }
        .total-participants {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin: 15px 0;
            padding: 8px;
            background-color: #f8f9fa;
        }
        .info-section {
            margin-top: 20px;
            page-break-inside: avoid;
        }
        .info-section p {
            margin: 5px 0;
            padding: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Отчет по конференции "{{ $conference->name }}"</h1>
        <p>Дата проведения: {{ \Carbon\Carbon::parse($conference->date_start)->format('d.m.Y') }} - {{ \Carbon\Carbon::parse($conference->date_end)->format('d.m.Y') }}</p>
        <p>Место проведения: {{ $conference->city->name }}, {{ $conference->address }}</p>
    </div>

    <div class="total-participants">
        Общее количество участников: {{ $totalParticipants }}
    </div>

    <div class="section">
        <h2>Статистика по секциям</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 40%">Название секции</th>
                    <th style="width: 20%">Количество участников</th>
                    <th style="width: 20%">Количество иногородних</th>
                    <th style="width: 20%">Количество докладов</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sections as $section)
                <tr>
                    <td>{{ $section['name'] }}</td>
                    <td>{{ $section['participants_count'] }}</td>
                    <td>{{ $section['non_resident_count'] }}</td>
                    <td>{{ $section['reports_count'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Статистика по уровню образования</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 60%">Уровень образования</th>
                    <th style="width: 20%">Количество</th>
                    <th style="width: 20%">Процент</th>
                </tr>
            </thead>
            <tbody>
                @foreach($educationStats as $stat)
                <tr>
                    <td>{{ $stat['name'] }}</td>
                    <td>{{ $stat['count'] }}</td>
                    <td>{{ $stat['percentage'] }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Статистика по местам обучения и работы</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 60%">Место обучения</th>
                    <th style="width: 20%">Количество</th>
                    <th style="width: 20%">Процент</th>
                </tr>
            </thead>
            <tbody>
                @foreach($studyPlaceStats as $stat)
                <tr>
                    <td>{{ $stat['name'] }}</td>
                    <td>{{ $stat['count'] }}</td>
                    <td>{{ $stat['percentage'] }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="info-section">
        <h2>Дополнительная информация</h2>
        <p>Формат проведения: {{ $conference->format->name }}</p>
        <p>Организатор: {{ $conference->organizer->surname }} {{ $conference->organizer->name }} {{ $conference->organizer->patronymic }}</p>
    </div>
</body>
</html>
