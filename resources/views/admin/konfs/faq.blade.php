@extends('layouts.admin')

@section('title', 'Добавление FAQ')

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/form.css') }}">
    <style>
        .faq-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .faq-item {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .faq-item input[type="text"] {
            width: 100%;
            margin-bottom: 10px;
        }
        .faq-item textarea {
            width: 100%;
            min-height: 100px;
            margin-bottom: 10px;
        }
        .button-container {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }
        .debug-info {
            margin-top: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
@endsection

@section('content')
    <div class="faq-container">
        <h1>Добавление вопросов для темы "{{ $theme->name }}"</h1>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="faqForm" action="{{ route('admin.konfs.store.faq', $theme->id) }}" method="POST">
            @csrf
            <div id="faq-items">
                <div class="faq-item">
                    <input type="text" name="questions[]" placeholder="Введите вопрос" required>
                    <textarea name="answers[]" placeholder="Введите ответ" required></textarea>
                </div>
            </div>
            
            <button type="button" class="button" onclick="addFaqItem()">Добавить вопрос</button>
            
            <div class="button-container">
                <button type="submit" class="button">Сохранить</button>
                <button type="button" class="button cancel-button" onclick="history.back()">Назад</button>
            </div>
        </form>

        <div class="debug-info">
            <h3>Отладочная информация:</h3>
            <p>Тема вопроса ID: {{ $theme->id }}</p>
            <p>Тема вопроса: {{ $theme->name }}</p>
            <p>Route name: {{ route('admin.konfs.store.faq', $theme->id) }}</p>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function addFaqItem() {
            const container = document.getElementById('faq-items');
            const newItem = document.createElement('div');
            newItem.className = 'faq-item';
            newItem.innerHTML = `
                <input type="text" name="questions[]" placeholder="Введите вопрос" required>
                <textarea name="answers[]" placeholder="Введите ответ" required></textarea>
                <button type="button" class="button remove-button" onclick="this.parentElement.remove()">Удалить</button>
            `;
            container.appendChild(newItem);
        }

        // Добавляем обработчик отправки формы
        document.getElementById('faqForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Собираем все вопросы и ответы
            const questions = Array.from(document.getElementsByName('questions[]')).map(input => input.value);
            const answers = Array.from(document.getElementsByName('answers[]')).map(input => input.value);
            
            // Проверяем, что все поля заполнены
            if (questions.some(q => !q) || answers.some(a => !a)) {
                alert('Пожалуйста, заполните все поля');
                return;
            }
            
            // Отправляем форму
            this.submit();
        });
    </script>
@endsection 