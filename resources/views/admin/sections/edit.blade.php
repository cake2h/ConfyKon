@extends('layouts.admin')
@section('title', 'Изменение секции ' . $section->name . ' в ' . $conference->name)

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/form.css') }}" />
@endsection

@section('content')

    <h2>Изменение секции {{ $section->name }} в конференции {{ $conference->name }}</h2>
    <form method="POST" action="{{ route('admin.sections.update', ['conference' => $conference->id, 'section' => $section->id]) }}" class="conference-form">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Название:</label>
            <input type="text" name="name" value="{{ $section->name }}" required>
        </div>

        <div class="form-group">
            <label for="event_date">Дата начала:</label>
            <input type="date" name="event_date" value="{{ $section->event_date }}" required>
        </div>

        <div class="form-group">
            <label for="event_place">Место проведения:</label>
            <input type="text" name="event_place" value="{{ $section->event_place }}" required>
        </div>

        <div class="form-group">
            <label for="mosderator_email">Email модератора:</label>
            <input type="email" name="moderator_email" value="{{ $moderatorEmail }}" required>
        </div>

        <div class="form-group">
            @error('moderator_email')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="button">Изменить</button>
    </form>
@endsection
