@extends('layouts.main')
@section('title', 'Помощь ИИ')

@section('some_styles')
<style>
    .ai-help-container {
        max-width: 1000px;
        margin: 40px auto;
        padding: 20px;
    }

    .ai-help-title {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #333;
    }

    .upload-section {
        background: #fff;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        width: 100%;
        box-sizing: border-box;
    }

    .file-upload {
        display: block;
        border: 2px dashed #ccc;
        border-radius: 6px;
        padding: 15px;
        text-align: center;
        margin: 0 auto 20px auto;
        cursor: pointer;
        transition: border-color 0.3s ease;
        box-sizing: border-box;
        width: 100%;
        overflow: hidden;
    }

    .file-upload:hover {
        border-color: #4a90e2;
    }

    .file-upload input[type="file"] {
        display: none;
    }

    .upload-icon {
        font-size: 48px;
        color: #4a90e2;
        margin-bottom: 10px;
    }

    .upload-text {
        color: #666;
        margin-bottom: 10px;
    }

    .selected-file {
        color: #4a90e2;
        margin-top: 10px;
        display: none;
    }

    .analyze-btn {
        background: #4a90e2;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
        width: 100%;
        transition: background-color 0.3s ease;
    }

    .analyze-btn:hover {
        background: #357abd;
    }

    .analyze-btn:disabled {
        background: #ccc;
        cursor: not-allowed;
    }

    .result-section, .bibliography-section {
        margin-top: 20px;
        display: none;
    }

    .result-title, .bibliography-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 10px;
        color: #333;
    }

    .result-text, .bibliography-text {
        background: #f5f5f5;
        border-radius: 6px;
        padding: 15px;
        /* white-space: pre-wrap;  ПОКА ЗАКОММЕНТИРУЕМ, чтобы <p> и <br> работали как обычно */
        font-size: 14px;
        line-height: 1.5; /* Стандартный line-height, можно попробовать уменьшить до 1.3 или 1.4, если строки слишком "разреженные" */
        color: #333;
        min-height: 100px;
        border: 1px solid #e0e0e0;
        text-align: left; /* Явно зададим выравнивание по левому краю */
    }

    /* Стили для HTML, сгенерированного из Markdown */
    .result-text strong, .bibliography-text strong, 
    .result-text b, .bibliography-text b {
        font-weight: bold;
    }

    /* Уменьшаем или убираем отступы у ВСЕХ блочных элементов внутри блоков с результатами */
    .result-text > *, 
    .bibliography-text > * {
        margin-top: 0.2em !important;
        margin-bottom: 0.2em !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }
    /* Более конкретно для параграфов, если предыдущее не сработает как надо */
    .result-text p, 
    .bibliography-text p {
        margin-top: 0.2em !important;
        margin-bottom: 0.2em !important;
        line-height: 1.4; /* Можно еще немного сжать высоту строки параграфа */
    }
    /* Отдельно для списков, если они используются */
    .result-text ul, .bibliography-text ul,
    .result-text ol, .bibliography-text ol {
        margin-top: 0.5em !important;
        margin-bottom: 0.5em !important;
        padding-left: 20px !important; /* Стандартный отступ для списков */
    }
    .result-text li, .bibliography-text li {
        margin-bottom: 0.1em !important;
    }

    .error-message {
        color: #dc3545;
        margin-top: 10px;
        padding: 10px;
        border: 1px solid #f5c6cb;
        border-radius: 4px;
        background-color: #f8d7da;
        display: none;
        white-space: pre-wrap;
    }

    .loading {
        display: none;
        text-align: center;
        margin: 20px 0;
    }

    .loading-spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #4a90e2;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 0 auto;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endsection

@section('content')
<div class="ai-help-container">
    <h1 class="ai-help-title">Помощь ИИ</h1>
    
    <div class="upload-section">
        <form id="documentForm" enctype="multipart/form-data">
            @csrf
            <label class="file-upload" for="document">
                <div class="upload-icon">
                    <i class="material-icons">cloud_upload</i>
                </div>
                <div class="upload-text">
                    Перетащите файл сюда или нажмите для выбора
                    <br>
                    <small>Поддерживаемые форматы: .doc, .docx (до 10MB)</small>
                </div>
                <input type="file" id="document" name="document" accept=".doc,.docx">
                <div class="selected-file" id="selectedFile"></div>
            </label>
            
            <button type="submit" class="analyze-btn" id="analyzeBtn" disabled>
                Анализировать документ
            </button>
        </form>

        <div class="loading" id="loading">
            <div class="loading-spinner"></div>
            <p>Анализируем документ... Это может занять несколько минут.</p>
        </div>

        <div class="error-message" id="errorMessage"></div>

        <div class="result-section" id="resultSection">
            <h2 class="result-title">Аннотация к докладу:</h2>
            <div class="result-text" id="resultText"></div>
        </div>

        <div class="bibliography-section" id="bibliographySection">
            <h2 class="bibliography-title">Анализ ссылок и списка литературы (ГОСТ Р 7.0.5–2008):</h2>
            <div class="bibliography-text" id="bibliographyText"></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('documentForm');
    const fileInput = document.getElementById('document');
    const selectedFile = document.getElementById('selectedFile');
    const analyzeBtn = document.getElementById('analyzeBtn');
    const loading = document.getElementById('loading');
    const errorMessage = document.getElementById('errorMessage');
    
    const resultSection = document.getElementById('resultSection');
    const resultText = document.getElementById('resultText');

    const bibliographySection = document.getElementById('bibliographySection');
    const bibliographyText = document.getElementById('bibliographyText');

    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            selectedFile.textContent = `Выбран файл: ${file.name}`;
            selectedFile.style.display = 'block';
            analyzeBtn.disabled = false;
            resultSection.style.display = 'none';
            bibliographySection.style.display = 'none';
            errorMessage.style.display = 'none';
            errorMessage.textContent = '';
        } else {
            selectedFile.style.display = 'none';
            analyzeBtn.disabled = true;
        }
    });

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        
        errorMessage.style.display = 'none';
        errorMessage.textContent = '';
        resultSection.style.display = 'none';
        bibliographySection.style.display = 'none';
        loading.style.display = 'block';
        analyzeBtn.disabled = true;

        try {
            const response = await fetch('{{ route("ai.analyze") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok && data.success) {
                const markedOptions = { breaks: true, gfm: true };

                if (data.annotation) {
                    resultText.innerHTML = marked.parse(data.annotation, markedOptions);
                    resultSection.style.display = 'block';
                } else {
                    resultText.textContent = 'Аннотация не была получена.';
                    resultSection.style.display = 'block';
                }

                if (data.bibliography) {
                    bibliographyText.innerHTML = marked.parse(data.bibliography, markedOptions);
                    bibliographySection.style.display = 'block';
                } else {
                    bibliographyText.textContent = 'Информация по библиографии не была получена.';
                    bibliographySection.style.display = 'block';
                }

            } else {
                let errorMsg = data.message || `Ошибка сервера: ${response.status}`;
                let displayedError = false;
                if (data.annotation && data.annotation.startsWith('Ошибка')) {
                    errorMessage.textContent += data.annotation + '\n';
                    displayedError = true;
                }
                if (data.bibliography && data.bibliography.startsWith('Ошибка')) {
                    errorMessage.textContent += data.bibliography + '\n';
                    displayedError = true;
                }
                if (!displayedError && errorMsg) {
                    errorMessage.textContent = errorMsg;
                }
                if (errorMessage.textContent) {
                    errorMessage.style.display = 'block';
                }
            }
        } catch (error) {
            console.error('Fetch error:', error);
            errorMessage.textContent = 'Произошла ошибка при отправке запроса: ' + error.message;
            errorMessage.style.display = 'block';
        } finally {
            loading.style.display = 'none';
            analyzeBtn.disabled = false;
        }
    });
});
</script>
@endsection 