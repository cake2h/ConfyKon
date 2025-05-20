<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Отчёт по конференции</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 14px;
            color: #2c3e50;
        }
        h1, h2 {
            color: #2c3e50;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        th, td {
            border: 1px solid #999;
            padding: 6px;
            text-align: left;
        }
        .progress {
            background-color: #f1f1f1;
            border-radius: 4px;
            overflow: hidden;
            height: 14px;
        }
        .progress-bar {
            height: 14px;
            background-color: #3498db;
        }
    </style>
</head>
<body>

    <h1>Отчёт по конференции</h1>

    <h2>Секции и количество участников</h2>
    <table>
        <thead>
            <tr>
                <th>Секция</th>
                <th>Количество участников</th>
                <th>Процент</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sections as $section)
                <tr>
                    <td>{{ $section['name'] }}</td>
                    <td>{{ $section['participants_count'] }}</td>
                    <td>
                        {{ $section['percentage'] }}%
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $section['percentage'] }}%;"></div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Уровни образования</h2>
    <table>
        <thead>
            <tr>
                <th>Уровень образования</th>
                <th>Количество</th>
                <th>Процент</th>
            </tr>
        </thead>
        <tbody>
            @foreach($educationStats as $stat)
                <tr>
                    <td>{{ $stat->name }}</td>
                    <td>{{ $stat->count }}</td>
                    <td> 
                        <p>{{ $stat->percentage }}%</p>
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $stat->percentage }}%;"></div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Учебные заведения</h2>
    <table>
        <thead>
            <tr>
                <th>Учебное заведение</th>
                <th>Количество</th>
                <th>Процент</th>
            </tr>
        </thead>
        <tbody>
            @foreach($studyPlaceStats as $stat)
                <tr>
                    <td>{{ $stat->name }}</td>
                    <td>{{ $stat->count }}</td>
                    <td>
                        <p>{{ $stat->percentage }}%</p>
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $stat->percentage }}%;"></div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
