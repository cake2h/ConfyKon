@extends('layouts.main')
@section('title', 'Личный кабинет')

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard/dashboard.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/dashboard/payments.css') }}" /> 
    <style>
        .comment-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .comment-modal-content {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 500px;
            max-height: 80vh;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            padding: 25px;
            overflow-y: auto;
        }

        .comment-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            position: sticky;
            top: 0;
            background: white;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .comment-modal-title {
            font-size: 20px;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        .comment-modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #666;
            padding: 0;
            line-height: 1;
        }

        .comment-text {
            font-size: 16px;
            line-height: 1.5;
            color: #333;
        }

        .comment-item {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .comment-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .comment-date {
            font-size: 14px;
            color: #666;
            margin-bottom: 8px;
        }

        .comment-content {
            white-space: pre-wrap;
        }

        .view-comment-btn {
            background: #4a90e2;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 15px;
            transition: background-color 0.3s ease;
            display: inline-block;
        }

        .view-comment-btn:hover {
            background: #357abd;
        }

        .application-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 10px;
        }
        .application-actions .link {
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 500;
            transition: background-color 0.2s;
            text-align: center;
        }
        .application-actions .link:first-child {
            background-color: #92d0fa;
            color: black;
        }
        .application-actions .link:first-child:hover {
            background-color: #7ab8e0;
        }
        .application-actions form {
            margin: 0;
            width: 100%;
        }
        .application-actions button.link {
            background-color: #f8f9fa;
            color: #dc3545;
            border: 1px solid #dc3545;
            width: 100%;
        }
        .application-actions button.link:hover {
            background-color: #dc3545;
            color: white;
        }
    </style>
@endsection

@section('content')
    <div class="user-info">
        <h2 class="user-name">{{ $user->surname }} {{ $user->name }} {{ $user->patronymic }}</h2>
        <ul class="user-details">
            <li><strong>Email:</strong> {{ $user->email }}</li>
            <li><strong>Номер телефона:</strong> {{ $user->phone_number }}</li>
            <li><strong>Дата рождения:</strong> {{ $user->birthday->format('d-m-Y') }}</li>
            <li><strong>Город:</strong> {{ $user->city->name }}</li>
            <li><strong>Уровень образования:</strong> {{ $user->education_level->name ?? 'Не указан' }}</li>
            <li><strong>Место обучения/работы:</strong> {{ $user->study_place->name ?? 'Не указано' }}</li>

            <br/>
            <a class="link" href="{{ route('profile.edit') }}">Редактирование профиля</a>
            <a class="link" href="{{ route('ai.help') }}">Помощь ИИ</a>
        </ul>
    </div>

    <div class="conference-applications">
        <h3>Мои регистрации</h3>
        @if (count($user->applications) === 0)
            <p>Вы не зарегистрированы ни на одну секцию</p>
        @else
            <div class="applications">
                @foreach($user->applications as $application)
                    <div class="application">
                        <div class="up">
                            <p>Конференция: {{ $application->section->conference->name }}</p>
                            <p>Cекция: {{ $application->section->name }}</p>
                            <p>Дата проведения: {{ \Carbon\Carbon::parse($application->section->date_start)->format('d-m-Y') }}</p>
                            <p>Место проведения: {{ $application->section->conference->address }}</p>
                            <p>Вид участия: {{ $application->participationType->name }}</p>
                            <p>Форма участия: {{ $application->presentationType->name }}</p>
                            @if($application->report)
                                <p>Название доклада: {{ $application->report->report_theme }}</p>
                            @endif
                            
                            @if($currentDate < \Carbon\Carbon::parse($application->section->conference->date_start)->subDays(3))
                                <div class="application-actions">
                                    <a href="{{ route('conf.edit_application', $application->id) }}" class="link">Редактировать</a>
                                    <form method="POST" action="{{ route('conf.delete_application', $application->id) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="link" style="color: red;" onclick="return confirm('Вы уверены, что хотите удалить эту регистрацию?')">Удалить</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                        <div class="down">
                            @if($application->role->name === 'Докладчик' || $application->role->name === 'Выступающий')
                                <p>Соавторы: {{ $application->contributors }}</p>

                                <a class="link @if(now() > \Carbon\Carbon::parse($application->section->conference->deadline_reports) || ($application->report && $application->report->file_path && $application->report->report_status_id != 3)) inactive @endif" onclick="openModal({{ $application->id }})">Прикрепить доклад</a>

                                @if ($application->report && $application->report->file_path)
                                    <p>
                                        Статус:
                                        <span class="status status-pending">{{ $application->report->reportStatus->name }}</span>
                                    </p>
                                    @if($application->report->reportStatus && $application->report->reportStatus->name === 'Отклонено')
                                        <button type="button" class="view-comment-btn" onclick="openCommentModal({{ $application->report->id }})">Посмотреть комментарии</button>
                                    @endif
                                @else
                                    <p>
                                        Статус:
                                        <span class="status status-pending">Не прикреплено</span>
                                    </p>
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="payments">
        <h3>Мои платежи</h3>

        <div class="balance">
            <span class="label">Ваш баланс:</span> {{ number_format($user->balance, 2, ',', ' ') }} руб.
        </div>

        <div class="actions">
            <button class="link" onclick="openPaymentModal()">Пополнить счёт</button>
        </div>

        @if($user->payments->count() > 0)
            <table class="payments__table">
                <thead>
                    <tr>
                        <th>Операция</th>
                        <th>Дата</th>
                        <th>Сумма платежа</th>
                        <th>Статус</th>
                        <th>Комментарий</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user->payments as $payment)
                        <tr>
                            <td class="idColumn">
                                <span class="type {{ $payment->type_class }}">{{ $payment->type_label }}</span>
                                <p>{{ $payment->id }}</p>
                            </td>
                            <td class="dateColumn">
                                {{ $payment->created_at->format('d.m.Y H:i:s') }}
                            </td>
                            <td class="amountColumn">
                                <span class="{{ $payment->type_class }}">{{ $payment->formatted_amount }}</span>
                            </td>
                            <td class="statusColumn">
                                {{ $payment->status_label }}
                            </td>
                            <td class="commentColumn">
                                {{ $payment->comment }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>У вас пока нет платежей</p>
        @endif
    </div>

    <div class="modal" id="imageModal">
        <div class="modal__container">
            <span class="close" onclick="closeModal()">&times;</span>
            <form method="POST" action="{{ route('conf.dock') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="application_id" id="application_id" value="">
                <h1>Прикрепление доклада</h1>
                <div class="form-group">
                    <label for="file">Файл:</label>
                    <input type="file" name="file" id="file" accept=".pdf,.doc,.docx" required>
                </div>
                <button class="button" type="submit">Отправить</button>
            </form>
        </div>
    </div>

    <!-- Модальное окно для просмотра комментария -->
    <div class="comment-modal" id="commentModal">
        <div class="comment-modal-content">
            <div class="comment-modal-header">
                <h2 class="comment-modal-title">Комментарии модератора</h2>
                <button class="comment-modal-close" onclick="closeCommentModal()">&times;</button>
            </div>
            <div class="comment-text" id="commentText"></div>
        </div>
    </div>

    <!-- Модальное окно для пополнения баланса -->
    <div class="modal" id="paymentModal">
        <div class="modal__container">
            <span class="close" onclick="closePaymentModal()">&times;</span>
            <h1>Пополнение баланса</h1>
            <form id="paymentForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                    <label for="amount">Сумма пополнения (руб.):</label>
                    <input type="number" name="amount" id="amount" min="1" max="100000" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="description">Описание (необязательно):</label>
                    <input type="text" name="description" id="description" maxlength="255">
                </div>
                <button class="button" type="submit">Оплатить</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function openModal(applicationId) {
            var modal = document.getElementById('imageModal');
            document.getElementById('application_id').value = applicationId;
            modal.style.display = 'block';
        }

        function closeModal() {
            var modal = document.getElementById('imageModal');
            modal.style.display = 'none';
        }

        function openCommentModal(reportId) {
            var modal = document.getElementById('commentModal');
            var commentText = document.getElementById('commentText');
            
            // Получаем комментарии через AJAX
            fetch(`/api/report/${reportId}/comments`)
                .then(response => response.json())
                .then(data => {
                    let commentsHtml = '';
                    data.forEach(comment => {
                        const date = new Date(comment.created_at).toLocaleString('ru-RU');
                        commentsHtml += `
                            <div class="comment-item">
                                <div class="comment-date">${date}</div>
                                <div class="comment-content">${comment.comment}</div>
                            </div>
                        `;
                    });
                    commentText.innerHTML = commentsHtml || 'Комментариев нет';
                })
                .catch(error => {
                    console.error('Ошибка при получении комментариев:', error);
                    commentText.innerHTML = 'Ошибка при загрузке комментариев';
                });
            
            modal.style.display = 'block';
        }

        function closeCommentModal() {
            var modal = document.getElementById('commentModal');
            modal.style.display = 'none';
        }

        // Закрытие модального окна при клике вне его
        window.onclick = function(event) {
            var modal = document.getElementById('commentModal');
            if (event.target == modal) {
                closeCommentModal();
            }
            
            var paymentModal = document.getElementById('paymentModal');
            if (event.target == paymentModal) {
                closePaymentModal();
            }
        }

        // Функции для работы с платежами
        function openPaymentModal() {
            var modal = document.getElementById('paymentModal');
            modal.style.display = 'block';
        }

        function closePaymentModal() {
            var modal = document.getElementById('paymentModal');
            modal.style.display = 'none';
            document.getElementById('paymentForm').reset();
        }

        // Обработка формы платежа
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            
            submitButton.textContent = 'Обработка...';
            submitButton.disabled = true;

            fetch('{{ route("payments.create") }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Перенаправляем на страницу оплаты ЮKassa
                    window.location.href = data.confirmation_url;
                } else {
                    console.log({data, er: data.error})
                    alert('Ошибка при создании платежа: ' + data.error);
                    submitButton.textContent = originalText;
                    submitButton.disabled = false;
                }
            })
            .catch(error => {
                console.log(error)
                console.error('Error:', error);
                alert('Произошла ошибка при создании платежа');
                submitButton.textContent = originalText;
                submitButton.disabled = false;
            });
        });
    </script>
@endsection
