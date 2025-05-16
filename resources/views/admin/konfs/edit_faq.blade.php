@extends('layouts.admin')

@section('title', 'Редактирование FAQ')

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/form.css') }}">
    <style>
        .faq-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .theme-section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .theme-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .theme-title {
            font-size: 1.2em;
            font-weight: bold;
            margin: 0;
        }
        .faq-item {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            position: relative;
            background-color: #f9f9f9;
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
        .remove-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .remove-button:hover {
            background-color: #c82333;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .add-faq-button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
        }
        .add-faq-button:hover {
            background-color: #218838;
        }
    </style>
@endsection

@section('content')
    <div class="faq-container">
        <h1>Редактирование FAQ для конференции "{{ $konf->name }}"</h1>
        
        @if (session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="error-message">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="error-message">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="faqForm" action="{{ route('admin.konfs.update.faq', $konf->id) }}" method="POST">
            @csrf
            <div id="faq-items">
                @php
                    $faqsByTheme = $faqs->groupBy('question_theme_id');
                @endphp

                @foreach($questionThemes as $theme)
                    <div class="theme-section" data-theme-id="{{ $theme->id }}">
                        <div class="theme-header">
                            <h3 class="theme-title">{{ $theme->name }}</h3>
                            <button type="button" class="add-faq-button" onclick="addFaqItem({{ $theme->id }})">
                                Добавить вопрос
                            </button>
                        </div>
                        
                        <div class="theme-faqs">
                            @foreach($faqsByTheme->get($theme->id, collect()) as $faq)
                                <div class="faq-item" data-faq-id="{{ $faq->id }}">
                                    <input type="hidden" name="faqs[{{ $loop->index }}][id]" value="{{ $faq->id }}">
                                    <input type="hidden" name="faqs[{{ $loop->index }}][question_theme_id]" value="{{ $theme->id }}">
                                    <input type="text" name="faqs[{{ $loop->index }}][name]" value="{{ $faq->name }}" placeholder="Введите вопрос" required>
                                    <textarea name="faqs[{{ $loop->index }}][answer]" placeholder="Введите ответ" required>{{ $faq->answer }}</textarea>
                                    <button type="button" class="remove-button" onclick="deleteFaq({{ $faq->id }})">Удалить</button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="button-container">
                <button type="submit" class="button">Сохранить изменения</button>
                <a href="{{ route('admin.konfs.edit', $konf->id) }}" class="button cancel-button">Назад</a>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        let faqIndex = {{ count($faqs) }};

        function addFaqItem(themeId) {
            const themeSection = document.querySelector(`[data-theme-id="${themeId}"] .theme-faqs`);
            const newItem = document.createElement('div');
            newItem.className = 'faq-item';
            newItem.innerHTML = `
                <input type="hidden" name="faqs[${faqIndex}][question_theme_id]" value="${themeId}">
                <input type="text" name="faqs[${faqIndex}][name]" placeholder="Введите вопрос" required>
                <textarea name="faqs[${faqIndex}][answer]" placeholder="Введите ответ" required></textarea>
                <button type="button" class="remove-button" onclick="this.parentElement.remove()">Удалить</button>
            `;
            themeSection.appendChild(newItem);
            faqIndex++;
        }

        function deleteFaq(faqId) {
            if (confirm('Вы уверены, что хотите удалить этот вопрос?')) {
                fetch(`/admin/konfs/{{ $konf->id }}/faq/${faqId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const faqItem = document.querySelector(`[data-faq-id="${faqId}"]`);
                        if (faqItem) {
                            faqItem.remove();
                        }
                    } else {
                        alert('Произошла ошибка при удалении вопроса');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Произошла ошибка при удалении вопроса');
                });
            }
        }

        document.getElementById('faqForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const questions = Array.from(document.querySelectorAll('input[name^="faqs"][name$="[name]"]')).map(input => input.value);
            const answers = Array.from(document.querySelectorAll('textarea[name^="faqs"][name$="[answer]"]')).map(input => input.value);
            
            if (questions.some(q => !q) || answers.some(a => !a)) {
                alert('Пожалуйста, заполните все поля');
                return;
            }
            
            this.submit();
        });
    </script>
@endsection 