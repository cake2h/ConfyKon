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
                    <a class="link" href="{{ route('admin.sections.index', [ 'conference' => $conference->id ]) }}">Секции</a>
                    <a class="link" href="{{ route('conf.edit', $conference->id) }}">Изменить информацию</a>
                    <form method="POST" action="{{ route('conf.destroy', $conference->id) }}" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="link" onclick="return confirm('Вы уверены?')">Удалить конференцию</button>
                    </form>
                </div>  
            </div> 
        @endforeach
    </div>
@endsection
