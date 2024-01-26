@extends('layouts.main')
@section('title', $conference->name)

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/main/conference.css') }}">
@endsection

@section('content')
    <div class="conference">
        <h1>{{ $conference->name }}</h1>
        <p class="section"><strong>Место проведения:</strong> {{ $conference->city }}, {{ $conference->country }}</p>
        <p class="section"><strong>Дата начала:</strong> {{ $conference->date_start }}</p>
        <p class="section"><strong>Дата окончания:</strong> {{ $conference->date_end }}</p>
        <p class="section"><strong>Крайний срок подачи заявок:</strong> {{ $conference->deadline }}</p>
        <p class="section">{!! nl2br($conference->description) !!}</p>

        <div class="conference__sections">
            <h2>Направления конференции</h2>
            <div class="conference__direction">
                <h3>Математические и компьютерные методы решения задач</h3>
                <p><strong>Ответственный:</strong> Иванов Иван Иванович</p>
            </div>
            <div class="conference__direction">
                <h3>Методы, технологии и программные средства обработки и анализа данных</h3>
                <p><strong>Ответственный:</strong> Иванов Иван Иванович</p>
            </div>
            <div class="conference__direction">
                <h3>Модели и алгоритмы искусственного интеллекта</h3>
                <p><strong>Ответственный:</strong> Иванов Иван Иванович</p>
            </div>
            <div class="conference__direction">
                <h3>Cовременные мобильные и Интернет-технологии</h3>
                <p><strong>Ответственный:</strong> Иванов Иван Иванович</p>
            </div>
        </div>

        <a class="link" href="{{ route('conf.show', $conference->id) }}">Записаться на конференцию</a>
    </div>
@endsection
