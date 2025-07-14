@extends('layouts.app')
@section('title')
@section('content')
    <h1>Tambah Program untuk {{ $langkah->name }}</h1>
    <a href="{{ route('teras.detail', [$langkah->teras_id, 'langkah_id' => $langkah->id]) }}" class="btn btn-secondary mb-3">Balik</a>
    <form action="{{ route('programs.store', $langkah) }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label for="program_name">Program Nama</label>
            <input type="text" name="program_name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Tambah</button>
    </form>
@endsection