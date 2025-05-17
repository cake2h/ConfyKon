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

    .result-section {
        margin-top: 20px;
        display: none;
    }

    .result-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 10px;
        color: #333;
    }

    .result-text {
        background: #f5f5f5;
        border-radius: 6px;
        padding: 15px;
        white-space: pre-wrap;
        font-size: 14px;
        line-height: 1.6;
        color: #333;
    }

    .error-message {
        color: #dc3545;
        margin-top: 10px;
        display: none;
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
            <p>Анализируем документ...</p>
        </div>

        <div class="error-message" id="errorMessage"></div>

        <div class="result-section" id="resultSection">
            <h2 class="result-title">Аннотация к докладу:</h2>
            <div class="result-text" id="resultText"></div>
        </div>
    </div>
</div>

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

    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            selectedFile.textContent = `Выбран файл: ${file.name}`;
            selectedFile.style.display = 'block';
            analyzeBtn.disabled = false;
        } else {
            selectedFile.style.display = 'none';
            analyzeBtn.disabled = true;
        }
    });

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        
        // Reset UI
        errorMessage.style.display = 'none';
        resultSection.style.display = 'none';
        loading.style.display = 'block';
        analyzeBtn.disabled = true;

        try {
            const response = await fetch('{{ route("ai.analyze") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const data = await response.json();

            if (data.success) {
                resultText.textContent = data.annotation;
                resultSection.style.display = 'block';
            } else {
                let errorMsg = data.message;
                if (data.error_details) {
                    errorMsg += '\nСтатус: ' + data.error_details.status;
                    if (data.error_details.response) {
                        errorMsg += '\nОтвет сервера: ' + JSON.stringify(data.error_details.response, null, 2);
                    }
                }
                throw new Error(errorMsg);
            }
        } catch (error) {
            errorMessage.textContent = error.message;
            errorMessage.style.display = 'block';
        } finally {
            loading.style.display = 'none';
            analyzeBtn.disabled = false;
        }
    });
});
</script>
@endsection 