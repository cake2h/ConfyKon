@extends('layouts.main')
@section('title', 'Личный кабинет')

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard/dashboard.css') }}" />
@endsection

@section('content')
    <div class="dashboard-container">
        <div class="user-info">
            <h2 class="user-name">{{ $user->surname }} {{ $user->name }} {{ $user->midname }}</h2>
            <ul class="user-details">
                <li><strong>Дата рождения:</strong> {{ $user->birthday }}</li>
                <li><strong>Почта:</strong> {{ $user->email }}</li>
                <li><strong>Город:</strong> {{ $user->city }}</li>
                <li><strong>Уровень образования:</strong> {{ $user->education_level->title }}</li>
                <li><strong>Место учебы:</strong> {{ $user->study_place }}</li>
            </ul>
        </div>

        <div class="conference-applications">
            <h3>Мои заявки</h3>
            @if (count(Auth::user()->applications) === 0)
                <p>Вы не отправили ни одной заявки.</p>
            @else
                <div class="applications">
                    @foreach(Auth::user()->applications as $application)
                        <div class="application">
                            <p>Конференция: {{ $application->section->konf->name }}</p>
                            <p>Cекция: {{ $application->section->name }}</p>
                            <p>Название работы: {{ $application->name }}</p>
                            <a class="link" href="#">Подробнее</a>
                        </div> 
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
