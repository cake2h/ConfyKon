@extends('layouts.main')

@section('title', 'Отчет по проверке')

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/main/report.css') }}">
@endsection

@section('content')
<div class="main__container">
    <h1>Отчет по проверке</h1>

    <p class="doc-id"><strong>ID документа:</strong> {{ $docId }}</p>

    @if($status) 
        <div class="report-summary">
            <h2>Результаты проверки подготавливаются</h2>
            <p>Перезагрузите страницу через несколько секунд.</p>
        </div>
    @else
         @php
            $result = $report->GetReportViewResult ?? null;
            $summary = $result->Summary ?? null;
            $detailed = $summary->DetailedScore ?? null;
            $sources = $result->CheckServiceResults->Sources ?? [];
        @endphp

         @if($summary)
            <div class="report-summary">
                <h2>Результаты проверки</h2>
                <p><strong>Дата готовности:</strong> {{ $summary->ReadyTime }}</p>
                <p><strong>Процент плагиата:</strong> {{ round($detailed->Plagiarism, 2) }}%</p>
                <p><strong>Уникальность:</strong> {{ round($detailed->Unknown, 2) }}%</p>
            </div>
        @endif

        @if(!empty($sources))
            <div class="report-sources">
                <h2>Источники заимствований</h2>
                <table>
                    <thead>
                        <tr>
                            <th>№</th>
                            <th>Источник</th>
                            <th>Совпадение (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sources as $index => $src)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <a href="{{ urldecode($src->Url) }}" target="_blank">
                                        {{ $src->Name }}
                                    </a>
                                </td>
                                <td>{{ round($src->ScoreBySource, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endif

    <div class="report-actions">
        <a href="{{ route('antiplagiat.upload.form') }}" class="btn">Вернуться назад</a>
    </div>
</div>
@endsection
