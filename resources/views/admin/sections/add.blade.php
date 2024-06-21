@extends('layouts.admin')
@section('title', 'Добавление секции в ' . $conference->name)

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/form.css') }}" />
@endsection

@section('content')
    <h2>Добавление секции к конференции {{ $conference->name }}</h2>
    <form method="POST" action="{{ route('admin.sections.store', $conference) }}" class="conference-form">
        @csrf

        <div class="form-group">
            <label for="name">Название:</label>
            <input type="text" name="name" required>
        </div>

        <div class="form-group">
            <label for="moderator_email">Email модератора:</label>
            <input type="email" name="moderator_email" required>
        </div>

        <div class="form-group">
            @error('moderator_email')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="button">Добавить</button>
    </form>

@endsection
