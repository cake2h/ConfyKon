@extends('layouts.admin')

@section('title', 'Редактирование конференции')

@section('content')
    <div class="container">
        <h1>Редактирование конференции</h1>
        <form action="{{ route('admin.konfs.update', $konf->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Название конференции:</label>
                <input type="text" name="name" id="name" value="{{ $konf->name }}" required>
            </div>

            <div class="form-group">
                <label for="description">Описание:</label>
                <textarea name="description" id="description" rows="4">{{ $konf->description }}</textarea>
            </div>

            <div class="form-group">
                <label for="date_start">Дата начала:</label>
                <input type="date" name="date_start" id="date_start" value="{{ $konf->date_start->format('Y-m-d') }}" required>
            </div>

            <div class="form-group">
                <label for="date_end">Дата окончания:</label>
                <input type="date" name="date_end" id="date_end" value="{{ $konf->date_end->format('Y-m-d') }}" required>
            </div>

            <div class="form-group">
                <label for="registration_deadline">Срок регистрации:</label>
                <input type="date" name="registration_deadline" id="registration_deadline" value="{{ $konf->registration_deadline ? $konf->registration_deadline->format('Y-m-d') : '' }}" required>
            </div>

            <div class="form-group">
                <label for="deadline">Срок подачи заявок:</label>
                <input type="date" name="deadline" id="deadline" value="{{ $konf->deadline->format('Y-m-d') }}" required>
            </div>

            <button type="submit">Сохранить изменения</button>
        </form>
    </div>
@endsection 