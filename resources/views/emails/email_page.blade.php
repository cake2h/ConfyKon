@extends('layouts.admin')
@section('title', 'Панель администратора')

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin/form.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin/email.css') }}" />
@endsection

@section('content')
    <div class="main__container">
        <div class="content-container">
            <div class="users">
                <h2>Адреса для отправки</h2>
                <select id="emailType">
                    <option value="">Выберите тип рассылки</option>
                    <optgroup label="Участники конференции">
                        @foreach($conferences as $conference)
                            <option value="conference" data-conference-id="{{ $conference->id }}">
                                {{ $conference->name }}
                            </option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Модераторы">
                        @foreach($conferences as $conference)
                            <option value="moderators" data-conference-id="{{ $conference->id }}">
                                {{ $conference->name }}
                            </option>
                        @endforeach
                    </optgroup>
                    <option value="custom">Загрузить</option>
                </select>

                <form method="POST" action="{{ route('send.emails') }}" id="emailForm" style="display: flex; flex-direction: column; align-items: flex-end;">
                    @csrf
                    <input type="hidden" name="attachments" id="selectedAttachments" value="">
                    <textarea id="emailList" name="emails" readonly ></textarea>
                    <button class="link" id="goBtn" type="submit" style="margin-top: 10px;">Рассылка</button>
                </form>
            </div>

            <div class="textBlock">
                <h2>Письмо для отправки</h2>
                <form action="{{ route('save.mail') }}" method="post" id="mailForm" style="display: flex; flex-direction: column; align-items: flex-end;">
                    @csrf
                    <textarea name="mail_text">{{ file_get_contents(resource_path('views/emails/mail.blade.php')) }}</textarea>
                    <div class="button-group">
                        <button class="link" id="saveBtn" type="submit">Сохранить</button>
                        <button class="link" id="filesBtn" type="button">Прикрепленные файлы</button>
                    </div>
                </form>
            </div>

            <!-- Модальное окно -->
            <div id="attachmentsModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <h3>Прикрепленные файлы</h3>
                    
                    <div class="attachments-container">
                        <div id="attachmentsList">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <label for="fileInput" class="file-upload-btn">Прикрепить файл</label>
                        <input type="file" id="fileInput" multiple>
                        <button class="btn-secondary" onclick="closeModal()">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@if (session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">Закрыть</button>
    </div>
@endif

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const debugInfo = document.getElementById('debugInfo');
            const emailList = document.getElementById('emailList');
            
            // Email type handling
            const emailType = document.getElementById('emailType');
            if (emailType) {
                emailType.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const type = selectedOption.value;
                    const conferenceId = selectedOption.dataset.conferenceId;

                    debugInfo.textContent = `Выбрано: ${type}, ID конференции: ${conferenceId}`;

                    if (type === 'custom') {
                        emailList.readOnly = false;
                        emailList.value = '';
                    } else {
                        emailList.readOnly = true;
                        fetchEmails(type, conferenceId);
                    }
                });
            }

            function fetchEmails(type, conferenceId) {
                debugInfo.textContent = `Загрузка данных... Тип: ${type}, ID: ${conferenceId}`;
                
                fetch('{{ route('get.emails') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ 
                        type: type,
                        conference_id: conferenceId
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Полученные данные:', data);
                    if (Array.isArray(data) && data.length > 0) {
                        const formattedData = data.map(user => `${user.name} - ${user.email}`).join("\n");
                        emailList.value = formattedData;
                        debugInfo.textContent = `Загружено ${data.length} записей`;
                    } else {
                        emailList.value = '';
                        debugInfo.textContent = 'Нет данных для отображения';
                    }
                })
                .catch(error => {
                    console.error('Ошибка:', error);
                    debugInfo.textContent = 'Ошибка при загрузке данных: ' + error.message;
                });
            }

            // Модальное окно
            const filesBtn = document.getElementById('filesBtn');
            const modal = document.getElementById('attachmentsModal');
            const closeBtn = document.querySelector('.close');
            const closeModalBtn = document.querySelector('.btn-secondary');

            if (filesBtn) {
                filesBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    modal.style.display = 'flex';
                });
            }

            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    modal.style.display = 'none';
                });
            }

            if (closeModalBtn) {
                closeModalBtn.addEventListener('click', function() {
                    modal.style.display = 'none';
                });
            }

            // Закрытие модального окна при клике вне его
            window.addEventListener('click', function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            });

            // File upload handling
            const fileInput = document.getElementById('fileInput');
            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    const files = e.target.files;
                    for (let i = 0; i < files.length; i++) {
                        uploadFile(files[i]);
                    }
                });
            }

            // Form submission handling
            const emailForm = document.getElementById('emailForm');
            if (emailForm) {
                emailForm.addEventListener('submit', function() {
                    updateSelectedAttachments();
                });
            }
        });

        function uploadFile(file) {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route('upload.attachment') }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    addAttachmentToList(data.attachment);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function addAttachmentToList(attachment) {
            const div = document.createElement('div');
            div.className = 'attachment-item';
            div.dataset.id = attachment.id;
            div.innerHTML = `
                <div style="text-align: center; margin-bottom: 10px;">
                    <span>${attachment.original_name}</span>
                    <button type="button" onclick="removeAttachment('${attachment.id}', '${attachment.file_path}')">Удалить</button>
                </div>
            `;
            document.getElementById('attachmentsList').appendChild(div);
            updateSelectedAttachments();
        }

        function removeAttachment(id, path) {
            fetch('{{ route('remove.attachment') }}', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ path: path })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const element = document.querySelector(`.attachment-item[data-id="${id}"]`);
                    if (element) {
                        element.remove();
                        updateSelectedAttachments();
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function updateSelectedAttachments() {
            const attachments = Array.from(document.querySelectorAll('.attachment-item')).map(item => {
                const button = item.querySelector('button');
                const path = button.getAttribute('onclick').match(/'([^']+)'/)[1];
                return {
                    id: item.dataset.id,
                    original_name: item.querySelector('span').textContent,
                    file_path: path
                };
            });
            const selectedAttachments = document.getElementById('selectedAttachments');
            if (selectedAttachments) {
                selectedAttachments.value = JSON.stringify(attachments);
            }
        }

        function closeModal() {
            const modal = document.getElementById('attachmentsModal');
            if (modal) {
                modal.style.display = 'none';
            }
        }
    </script>
@endsection
