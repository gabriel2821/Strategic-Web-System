@extends('layouts.app')
@section('title')
@section('content')

        <h1>Tambah Teras</h1>
        <a href="{{ route('teras.index') }}" class="btn btn-secondary">Balik</a>
        <form action="{{ route('teras.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Title</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Tambah</button>
        </form>
@endsection