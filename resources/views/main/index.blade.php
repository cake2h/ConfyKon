@extends('layouts.main')
@section('title', 'Главная')

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/form.css') }}">
    <link rel="stylesheet" href="{{asset('css/main/conference.css')}}" />
@endsection

@php
    use Carbon\Carbon;

    if (Auth::user()) {
        $birthday = Auth::user()->birthday;
        $age = Carbon::parse($birthday)->age;
    }
@endphp

@section('content')
    <div class="conferences">
        @foreach($conferences as $conference)
            <div class="conference">
                <h2 class="title">{{ $conference->name }}</h2>

                <div class="simple__info">
                    <p>Место проведения: {{ $conference->address }}</p>
                    <p>Дата проведения: {{ Carbon::parse($conference->date_start)->format('d-m-Y') }} - {{ Carbon::parse($conference->date_end)->format('d-m-Y') }} </p>
                    <p>Срок регистрации на конфернецию до: <span style="color: #ff0000">{{ Carbon::parse($conference->date_start)->subDays(3)->format('d-m-Y') }} </span></p>
                    <p>Срок загрузки публикаций до: <span style="color: #ff0000">{{ Carbon::parse($conference->deadline)->addDays(7)->format('d-m-Y') }} </span></p>
                </div>

                <p>{!! nl2br(e($conference->description)) !!}</p>

                @auth
                    @if($age < 35 and now() < Carbon::parse($conference->date_start)->subDays(2))
                        <p class="link" onclick="openModal()">Регистрация на конфренцию</p>
                    @elseif ($age > 35)
                        <button class="link" style="color: gray; opacity: 0.5" disabled>Ваш возраст превышает допустимый</button>
                    @else
                        <button class="link" style="color: gray; opacity: 0.5" disabled>Регистрация закончилась</button>
                    @endif
                @else
                    <p class="message">Чтобы отправить заявку на участие, необходимо зарегистрироваться</p>
                @endauth

                <h2>Направления конференции</h2>
                <div class="sections">
                    @foreach($conference->sections as $section)
                        <div class="section">
                            <h3>{{ $section->name }}</h3>
                            <p class="moder"><strong>Ответственный: </strong>{{ $section->moder->surname }} {{ $section->moder->name }}</p>
                            <p class="moder"><strong>Email: </strong>{{ $section->moder->email }}</p>
                        </div>
                    @endforeach


                </div>
            </div>

            <div class="modal" id="imageModal">
                <div class="modal__container">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <form method="POST" action="{{ route('conf.subscribe', $conference) }}" enctype="multipart/form-data">
                        @csrf
                        <h2 style="margin-left:40px">Регистрация на конференцию</h2>
                        <div class="form-group">
                            <label for="name">Наименование доклада:</label>
                            <input type="text" name="name" required>
                        </div>

                        <div class="form-group">
                            <label for="otherAuthors">Соавторы (ФИО <b>полностью</b> через запятую):</label>
                            <input type="text" name="otherAuthors">
                        </div>

                        <div class="form-group">
                            <label for="section_id">Секция:</label>
                            <select id="section_id" class="authInput" name="section_id">
                                <option value="" disabled selected hidden>Секция</option>
                                @foreach($conference->sections as $section)
                                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="presentation_type_id">Форма участия:</label>
                            <select id="presentation_type_id" class="authInput" name="presentation_type_id">
                                <option value="" disabled selected hidden>Выберите форму</option>
                                @foreach($presentationTypes as $type)
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
