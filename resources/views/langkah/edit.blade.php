@extends('layouts.app')
@section('title')
@section('content')
        <h1>Edit Langkah untuk {{ $teras->name }}</h1>
        <a href="{{ route('langkah.index', $teras) }}" class="btn btn-secondary">Balik</a>
        <form action="{{ route('langkah.update', [$teras, $langkah]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" name="name" class="form-control" value="{{ $langkah->name }}" required>
            </div>
            <div class="form-group">
                <label for="description">Penerangan</label>
                <textarea name="description" class="form-control">{{ $langkah->description }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Kemas kini</button>
        </form>
    </div>
</body>
</html>
@endsection