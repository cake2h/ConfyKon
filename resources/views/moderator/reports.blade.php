@extends('layouts.admin')
@section('title', 'Доклады секции')

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}" />
    <style>
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .modal-window {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 800px;
            height: 500px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .modal-header {
            padding: 25px 30px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
            flex-shrink: 0;
        }

        .modal-title {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: #666;
            padding: 0;
            line-height: 1;
        }

        .modal-body {
            padding: 70px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex: 1;
            overflow-y: auto;
        }

        .comment-input {
            position: relative;
            width: 600px;
        }

        .comment-input textarea {
            margin-top: -20%;
            width: 95%;

            padding: 12px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            resize: none;
            font-family: inherit;
            font-size: 15px;
            line-height: 1.5;
            color: #333;
            background: #fff;
            transition: all 0.3s ease;
        }

        .comment-input textarea:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }

        .comment-input textarea::placeholder {
            color: #999;
        }

        .modal-footer {
            padding: 25px 30px;
            border-top: 1px solid #eee;
            text-align: right;
            background: #f8f9fa;
            flex-shrink: 0;
        }

        .modal-button {
            background: #4a90e2;
            color: white;
            border: none;
            padding: 12px 35px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .modal-button:hover {
            background: #357abd;
        }

        #rejectForm {
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100%;
            width: 100%;
        }
    </style>
@endsection

@section('content')
    <div class="conferences">
        <div class="conference">
            <h1 class="title">{{ $section->name }}</h1>
            <a href="{{ route('moderator.index') }}" class="link">← Назад к заявкам</a>
            
            @if($applicants->count() > 0)
                <ul>
                    @foreach ($applicants as $applicant)
                        <li>
                            <span>{{ $applicant->user_name }} - {{ $applicant->work_name }}</span>
                            @if ($applicant->file_path)
                                <a href="{{ asset($applicant->file_path) }}" class="download-link">Скачать файл</a>
                            @endif
                            <div class="button-group">
                                <form action="{{ route('application.approve', $applicant->application_id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="approve-button">Одобрить</button>
                                </form>
                                <button type="button" class="reject-button" onclick="openRejectModal({{ $applicant->application_id }})">Отклонить</button>
                            </div>
                            <span class="status status-pending">{{ $applicant->report_status }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>Загруженных статей нет</p>
            @endif
        </div>
    </div>

    <!-- Модальное окно для отклонения -->
    <div class="modal-overlay" id="rejectModal">
        <div class="modal-window">
            <div class="modal-header">
                <h2 class="modal-title">Отклонение доклада</h2>
                <button class="modal-close" onclick="closeRejectModal()">&times;</button>
            </div>
            <form method="POST" id="rejectForm">
                @csrf
                <div class="modal-body">
                    <div class="comment-input">
                        <textarea name="comment" placeholder="Укажите причину отклонения доклада..." required></textarea>
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <button type="submit" form="rejectForm" class="modal-button">Отклонить</button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function openRejectModal(applicationId) {
            var modal = document.getElementById('rejectModal');
            var form = document.getElementById('rejectForm');
            form.action = `/application/${applicationId}/reject`;
            modal.style.display = 'block';
        }

        function closeRejectModal() {
            var modal = document.getElementById('rejectModal');
            modal.style.display = 'none';
        }

        // Закрытие модального окна при клике вне его
        window.onclick = function(event) {
            var modal = document.getElementById('rejectModal');
            if (event.target == modal) {
                closeRejectModal();
            }
        }
    </script>
@endsection 