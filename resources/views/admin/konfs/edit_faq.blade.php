@extends('layouts.admin')

@section('title', 'Редактирование FAQ')

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/form.css') }}">
    <style>
        .profile-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 40px auto;
            position: relative;
            padding-bottom: 80px;
        }
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .header-section h1 {
            margin: 0;
            flex: 1;
        }
        .theme-section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
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
            background-color: white;
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
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            display: flex;
            gap: 15px;
            justify-content: center;
            padding: 20px;
            background-color: #f9f9f9;
            border-top: 1px solid #eee;
            border-radius: 0 0 8px 8px;
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
            background-color: #92d0fa;
            color: black;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
        }
        .add-faq-button:hover {
            background-color: #7ab8e0;
        }
        .button {
            background-color: #92d0fa;
            color: black;
            border: none;
            border-radius: 6px;
            padding: 8px 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .button:hover {
            background-color: #7ab8e0;
        }
        .cancel-button {
            background-color: #f8f9fa;
            color: #333;
            border: 1px solid #ddd;
        }
        .cancel-button:hover {
            background-color: #e9ecef;
        }
        .header-section .button-container .button {
            background-color: #92d0fa;
            color: black;
            border: none;
            border-radius: 6px;
            padding: 8px 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            white-space: nowrap;
            flex-shrink: 0;
            width: auto !important;
            margin: 0;
        }
        .header-section .button-container .button:hover {
            background-color: #7ab8e0;
        }
        .header-section .button-container .cancel-button {
            background-color: #f8f9fa;
            color: #333;
            border: 1px solid #ddd;
        }
        .header-section .button-container .cancel-button:hover {
            background-color: #e9ecef;
        }
    </style>
@endsection

@section('content')
    <div class="profile-container">
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
                <button type="submit" class="button" style="background-color: #92d0fa; color: black; border: none; border-radius: 6px; padding: 12px 32px; font-weight: 600; cursor: pointer; transition: background 0.2s;">Сохранить изменения</button>
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