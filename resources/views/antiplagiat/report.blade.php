@extends('layouts.antiplagiat')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white shadow-md rounded-xl">
    <h1 class="text-2xl font-bold mb-4">Отчет по проверке</h1>

    <p><strong>ID документа:</strong> {{ $docId }}</p>

    @if(isset($report->HtmlView))
        <div class="border p-4 rounded bg-gray-50 mt-4">
            {!! $report->HtmlView !!}
        </div>
    @else
        <pre class="mt-4 bg-gray-100 p-4 rounded">
            {{ print_r($report, true) }}
        </pre>
    @endif

    <div class="mt-4">
        <a href="{{ route('antiplagiat.upload.form') }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Проверить ещё документ
        </a>
    </div>
</div>
@endsection
