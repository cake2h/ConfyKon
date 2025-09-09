@extends('layouts.antiplagiat')

@section('content')
<div class="max-w-2xl mx-auto p-6 bg-white shadow-md rounded-xl">
    <h1 class="text-2xl font-bold mb-4">Проверка документа на заимствования</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('antiplagiat.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label class="block font-semibold mb-2">Загрузите документ</label>
            <input type="file" name="file" class="w-full border rounded p-2" required>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Отправить на проверку
        </button>
    </form>
</div>
@endsection
