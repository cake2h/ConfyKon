@extends('layouts.main')
@section('title', 'Редактирование заявки')

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/form.css') }}">
    <style>
        .speaker-fields {
            display: {{ in_array($application->role->name, ['Слушатель']) ? 'none' : 'block' }};
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <h1>Редактирование заявки</h1>
        
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('conf.update_application', $application->id) }}" class="form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="role_id">Роль участия:</label>
                <select id="role_id" class="authInput" name="role_id" required onchange="toggleFields()">
                    <option value="" disabled selected hidden>Выберите роль</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" data-name="{{ $role->name }}" {{ $application->role_id == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div id="speakerFields" class="speaker-fields">
                <div class="form-group">
                    <label for="name">Название доклада</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $application->name) }}" {{ $application->role->name !== 'Слушатель' ? 'required' : '' }}>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="otherAuthors">Соавторы (ФИО <b>полностью</b> через запятую):</label>
                    <input type="text" name="otherAuthors" id="otherAuthors" value="{{ $application->otherAuthors }}">
                </div>
            </div>

            <div class="form-group">
                <label for="presentation_type_id">Форма участия:</label>
                <select id="presentation_type_id" class="authInput" name="presentation_type_id" required>
                    <option value="" disabled selected hidden>Выберите форму участия</option>
                    @foreach($presentationTypes as $type)
                        <option value="{{ $type->id }}" {{ $application->type_id == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="button">Сохранить изменения</button>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        function toggleFields() {
            const roleSelect = document.getElementById('role_id');
            const speakerFields = document.getElementById('speakerFields');
            const nameInput = document.getElementById('name');
            
            if (!roleSelect || !speakerFields || !nameInput) return;
            
            const selectedOption = roleSelect.options[roleSelect.selectedIndex];
            if (!selectedOption) return;
            
            const roleName = selectedOption.textContent.trim();
            const isSpeaker = roleName !== 'Слушатель';
            
            console.log('Role:', roleName, 'Is speaker:', isSpeaker);
            
            speakerFields.style.display = isSpeaker ? 'block' : 'none';
            nameInput.required = isSpeaker;
        }

        // Устанавливаем начальное состояние при загрузке страницы
        document.addEventListener('DOMContentLoaded', function() {
            toggleFields();
        });
    </script>
@endsection 