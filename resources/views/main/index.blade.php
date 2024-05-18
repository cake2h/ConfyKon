@extends('layouts.main')
@section('title', 'Главная')

@section('some_styles')

    <link rel="stylesheet" href="{{ asset('css/admin/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main/conference.css') }}" />
@endsection

@section('content')
    <div class="conferences">
        @foreach ($conferences as $conference)
            <div class="conference">
                <h1 class="title">{{ $conference->name }}</h1>

                <div class="simple__info">
                    <p>Место проведения: {{ $conference->country }}, {{ $conference->city }}
                    <p>Дата проведения: {{ $conference->date_start }} - {{ $conference->date_end }}</p>
                    <p>Крайний срок подачи заявок: {{ $conference->deadline }}</p>
                </div>


                <p>{!! nl2br($conference->description) !!}</p>

                @php
                    $startDateMinusOneDay = \Carbon\Carbon::parse($conference->date_start)->subDay();
                    $currentDate = \Carbon\Carbon::now();
                @endphp

                @if ($currentDate->gt($startDateMinusOneDay))
                    <p class="link">Запись закончилась</p>
                @else
                    @auth
                        <p class="link" onclick="openModal()">Записаться</p>
                    @else
                        <p class="message">Чтобы отправить заявку на участие, необходимо зарегистрироваться</p>
                    @endauth
                @endif

                <h2>Направления конференции</h2>
                <div class="sections">
                    @foreach ($conference->sections as $section)
                        <div class="section">
                            <h3>{{ $section->name }}</h3>
                            <p class="moder"><strong>Ответственный: </strong>{{ $section->moder->surname }}
                                {{ $section->moder->name }}</p>
                            <p>
                                {!! strlen($section->description) > 410 ? substr($section->description, 0, 410) . '...' : $section->description !!}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="modal" id="imageModal">
                <div class="modal__container">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <form method="POST" action="{{ route('conf.subscribe', $conference) }}" enctype="multipart/form-data">
                        @csrf
                        <h1>Записаться на конференцию</h1>
                        <div class="form-group">
                            <label for="name">Название работы:</label>
                            <input type="text" name="name" required>
                        </div>

                        <div class="form-group">
                            <label for="section_id">Cекция:</label>
                            <select id="section_id" class="authInput" name="section_id">
                                <option value="" disabled selected hidden>Секция</option>
                                @foreach ($conference->sections as $section)
                                    <option name="section_id" value="{{ $section->id }}">{{ $section->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="presentation_type_id">Форма выступления:</label>
                            <select id="presentation_type_id" class="authInput" name="presentation_type_id">
                                <option value="" disabled selected hidden>Выберите форму</option>
                                @foreach ($presentationTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button class="button" type="submit">Отправить</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@section('scripts')
    <script>
        function openModal() {
            var modal = document.getElementById('imageModal');

            modal.style.display = 'block';
        }

        function closeModal() {
            var modal = document.getElementById('imageModal');
            modal.style.display = 'none';
        }
    </script>
@endsection
