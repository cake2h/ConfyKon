@extends('layouts.antiplagiat')

@section('content')
<div class="max-w-xl mx-auto p-6 bg-white shadow-md rounded-xl">
    <h1 class="text-2xl font-bold mb-4">Статус проверки</h1>

    <p><strong>ID документа:</strong> {{ $docId }}</p>
    <p><strong>Статус:</strong> {{ $status->ReportStatus ?? 'Неизвестно' }}</p>

    @if(($status->ReportStatus ?? '') !== 'Ready')
        <div class="mt-4 p-3 bg-yellow-100 text-yellow-800 rounded">
            Проверка ещё в процессе... Обновите страницу через несколько секунд.
        </div>
        <div class="mt-4">
            <a href="{{ route('antiplagiat.status', ['docId' => $docId]) }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Обновить статус
            </a>
        </div>
    @else
        <div class="mt-4">
            <a href="{{ route('antiplagiat.report', ['docId' => $docId]) }}" 
               class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Смотреть отчет
            </a>
        </div>
    @endif
</div>
@endsection
