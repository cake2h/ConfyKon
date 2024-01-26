@extends('layouts.main')
@section('title', 'Главная')

@section('some_styles')
    <link rel="stylesheet" href="{{asset('css/main/conferences.css')}}" />
@endsection

@section('content')
    <div class="conferences">
        @foreach($conferences as $conference)
            <div class="conference">
                <h1 class="title">{{ $conference->name }}</h1>
                <p class="date">Дата начала: {{ $conference->date_start }}</p>
                <p class="date">{!! nl2br($conference->description) !!}</p>
                <a class="link" href="{{ route('conf.show', $conference->id) }}">Подробнее</a>
            </div> 
        @endforeach
    </div>
    
@endsection