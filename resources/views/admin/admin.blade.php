@extends('layouts.admin')
@section('title', 'Панель администратора')

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}" />
@endsection

@section('content')
    <div class="conferences">
        @if(count($conferences) > 0)
            <div class="create__conf">
                <a class="link" href="{{ route('conf.add') }}">Создать конференецию</a>
            </div>
            @foreach ($conferences as $conference)
                <div class="conference">
                    <h2 class="title">{{ $conference->name }}</h2>
                    <div class="simple__info">
                        <p>Место проведения: {{ $conference->address }}</p>
                        <p>Дата проведения: {{ \Carbon\Carbon::parse($conference->date_start)->format('d-m-Y') }} - {{ \Carbon\Carbon::parse($conference->date_end)->format('d-m-Y') }} </p>
                        <p>Срок регистрации на конфернецию до: <span style="color: #ff0000">{{ \Carbon\Carbon::parse($conference->date_start)->subDays(3)->format('d-m-Y') }} </span></p>
                        <p>Срок загрузки публикаций до: <span style="color: #ff0000">{{ \Carbon\Carbon::parse($conference->deadline)->addDays(7)->format('d-m-Y') }} </span></p>
                    </div>

                    <p class="date">{!! nl2br($conference->description) !!}</p>
                    <div class="actions">
                        <a class="link" href="{{ route('admin.sections.add', $conference) }}">Добавить секцию</a>
                        <a class="link" href="{{ route('conf.edit', $conference->id) }}">Редактировать конференцию</a>
                        <form method="POST" action="{{ route('conf.destroy', $conference->id) }}" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="link" onclick="return confirm('Вы уверены?')">Удалить конференцию</button>
                        </form>
                    </div>
                    <h2>Направления конференции</h2>
                    <div class="sections">
                        @foreach ($conference->sections as $section)
                            <div class="section">
                                <h3>{{ $section->name }}</h3>
                                <div class="up">
                                    <p class="moder"><strong>Ответственный: </strong>{{ $section->moder->surname }} {{ $section->moder->name }} {{ $section->moder->midname }}</p>
                                    <div class="section__actions">
                                        <a class="section__link" href="{{ route('admin.sections.update', ['conference' => $conference->id, 'section' => $section->id]) }}">Редактировать</a>
                                        <form method="POST" action="{{ route('admin.sections.destroy', ['conference' => $conference->id, 'section' => $section->id]) }}" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="section__link" onclick="return confirm('Вы уверены?')">Удалить</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

        @else
            <div class="create__conf">
                <a class="link" href="{{ route('conf.add') }}">Создать конференецию</a>
            </div>
        @endif

    </div>
@endsection

@if (session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">Закрыть</button>
    </div>
@endif
