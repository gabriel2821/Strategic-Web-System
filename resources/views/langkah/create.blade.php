@extends('layouts.app')
@section('title')
@section('content')
        <h1>Tambah Langkah untuk {{ $teras->name }}</h1>
        <a href="{{ route('langkah.index', $teras) }}" class="btn btn-secondary">Balik</a>
        <form action="{{ route('langkah.store', $teras) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Penerangan</label>
                <textarea name="description" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</body>
</html>

@endsection