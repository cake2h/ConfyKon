@extends('layouts.admin')
@section('title', 'Панель администратора')

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}" />
@endsection

@section('content')
    <div class="conferences">
        @foreach($conferences as $conference)   
            <div class="conference">
                <h1 class="title">{{ $conference->name }}</h1>
                <p class="date">Дата начала: {{ $conference->date_start }}</p>
                <p class="date">{!! nl2br($conference->description) !!}</p>
                <div class="controls">
                    <a class="link" href="{{ route('conf.show', $conference->id) }}">Подробнее</a>
                    <a class="link" href="{{ route('conf.show', $conference->id) }}">Изменить информацию</a>
                    <a class="link" href="{{ route('conf.show', $conference->id) }}">Секции</a>
                </div>  
            </div> 
        @endforeach
    </div>
@endsection
