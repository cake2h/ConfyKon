@extends('layouts.admin')
@section('title', 'Секции ' . $conference->name)

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/sections.css') }}" />
@endsection

@section('content')
    <a href="{{ route('admin.sections.add', $conference) }}">Добавить секцию</a>
    <div class="sections">
        @foreach ($conference->sections as $section)
            <div class="section">
                <p>{{ $section->name }}</p>
                <a href="{{ route('admin.sections.update', ['conference' => $conference->id, 'section' => $section->id]) }}">Изменить</a>
                <form method="POST" action="{{ route('admin.sections.destroy', ['conference' => $conference->id, 'section' => $section->id]) }}" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="link" onclick="return confirm('Вы уверены?')">Удалить секцию</button>
                </form>
            </div>
        @endforeach
    </div>
    
@endsection



