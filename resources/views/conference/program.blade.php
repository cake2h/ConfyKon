@extends('layouts.admin')
@section('title', 'Программа конференции')

@section('some_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}" />
@endsection

@section('content')
    <div class="conferences">
        <h1 class="title">Программа конференции</h1>

        @foreach($sections as $section)
            <div class="conference">
                <h2 class="title">{{ $section->name }}</h2>
                <div class="simple__info">
                    <p>Время проведения: {{ \Carbon\Carbon::parse($section->date_start)->format('d.m.Y H:i') }} - 
                    {{ \Carbon\Carbon::parse($section->date_end)->format('H:i') }}</p>
                    @if($section->description)
                        <p class="date">{!! nl2br($section->description) !!}</p>
                    @endif
                </div>

                <div class="sections">
                    @forelse($section->applications as $application)
                        <div class="section">
                            <h3>{{ $application->user->last_name }} {{ $application->user->first_name }}</h3>
                            <p class="date">{{ $application->report->report_theme }}</p>
                        </div>
                    @empty  
                        <p class="date">Нет заявок на участие в данной секции</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
@endsection 