@extends('layouts.main')
@section('title', 'Редактирование заявки')

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/form.css') }}">
    <style>
        .speaker-fields {
            display: {{ $application->participation_type_id == 1 ? 'none' : 'block' }};
        }
        .profile-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 40px auto;
            overflow: hidden;
        }
        .save-button {
            background-color: #92d0fa;
            color: black;
            border: none;
            border-radius: 6px;
            padding: 12px 32px;
            font-weight: 600;
            cursor: pointer;
            margin: 30px auto 0 auto;
            display: block;
            transition: background 0.2s;
        }
        .save-button:hover {
            background-color: #92d0fa;
        }
        .form-group {
            margin-bottom: 20px;
            width: 100%;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        .form-control, .authInput, select {
            width: 90%;
            max-width: 90%;
            padding: 6px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            margin: 0;
            font-size: 14px;
        }
        .form-control:focus, .authInput:focus, select:focus {
            outline: none;
            border-color: #92d0fa;
        }
        .form {
            width: 100%;
            max-width: 100%;
        }
    </style>
@endsection

@section('content')
    <div class="profile-container">
        <h1 style="text-align:center;">Редактирование регистрации</h1>
        
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('conf.update_application', $application->id) }}" class="form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="participation_type_id">Роль участия:</label>
                <select id="participation_type_id" class="authInput" name="participation_type_id" required onchange="toggleFields()">
                    <option value="" disabled selected hidden>Выберите роль</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ $application->participation_type_id == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div id="speakerFields" class="speaker-fields">
                <div class="form-group">
                    <label for="name">Название доклада</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $application->report ? $application->report->report_theme : '') }}" {{ $application->participation_type_id != 1 ? 'required' : '' }}>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="contributors">Соавторы (ФИО <b>полностью</b> через запятую):</label>
                    <input type="text" name="contributors" id="contributors" class="form-control" value="{{ old('contributors', $application->contributors) }}">
                </div>
            </div>

            <div class="form-group">
                <label for="presentation_type_id">Форма участия:</label>
                <select id="presentation_type_id" class="authInput" name="presentation_type_id" required>
                    <option value="" disabled selected hidden>Выберите форму участия</option>
                    @foreach($presentationTypes as $type)
                        <option value="{{ $type->id }}" {{ $application->presentation_type_id == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="save-button">Сохранить изменения</button>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        function toggleFields() {
            const roleSelect = document.getElementById('participation_type_id');
            const speakerFields = document.getElementById('speakerFields');
            const nameInput = document.getElementById('name');
            
            if (!roleSelect || !speakerFields || !nameInput) return;
            
            const selectedOption = roleSelect.options[roleSelect.selectedIndex];
            if (!selectedOption) return;
            
            const roleId = parseInt(selectedOption.value);
            const isSpeaker = roleId !== 1;
            
            speakerFields.style.display = isSpeaker ? 'block' : 'none';
            nameInput.required = isSpeaker;
        }

        // Устанавливаем начальное состояние при загрузке страницы
        document.addEventListener('DOMContentLoaded', function() {
            toggleFields();
        });
    </script>
@endsection 