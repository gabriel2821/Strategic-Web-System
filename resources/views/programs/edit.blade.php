@extends('layouts.app')
@section('title')
@section('content')
    <h1>Urus Program: {{ $program->program_name }}</h1>
    <a href="{{ route('teras.detail', [$langkah->teras_id, 'langkah_id' => $langkah->id]) }}" class="btn btn-secondary mb-3">Balik</a>
    <form action="{{ route('programs.update', [$langkah, $program]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group mb-3">
            <label for="program_name">Program Nama</label>
            <input type="text" name="program_name" class="form-control" value="{{ $program->program_name }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Kemas Kini</button>
    </form>
@endsection