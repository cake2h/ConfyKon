@extends('layouts.main')

@section('title', 'Проверка документа на заимствования')

@section('some_styles')
<link rel="stylesheet" href="{{ asset('css/main/antiplagiat.css') }}" />
@endsection

@section('content')

<div class="main__container">
    <h1>Проверка документа на заимствования</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('antiplagiat.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div>
            <label for="file">Загрузите документ:</label>
            <input type="file" id="file" name="file" required>
        </div>

        <button type="submit" class="button">Отправить на проверку</button>
    </form>

    <h1>Ваши проверки</h1>

    @if($reports->isEmpty())
        <p class="placeholder">Проверок нет</p>
    @else
        <table class="reports-table">
            <thead>
                <tr>
                    <th>ID документа</th>
                    <th>Название</th>
                    <th>Дата</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $report)
                    <tr>
                        <td>{{ $report->doc_id }}</td>
                        <td>{{ $report->title }}</td>
                        <td>{{ $report->created_at->format('d.m.Y H:i') }}</td>
                        <td>
                            <a href="{{ route('antiplagiat.report', ['docId' => $report->doc_id]) }}" class="link-button">Просмотр</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@endsection
