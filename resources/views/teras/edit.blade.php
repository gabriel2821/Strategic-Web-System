@extends('layouts.app')
@section('title')
@section('content')
        <h1>Edit Teras untuk {{ $teras->name }}</h1>
        <a href="{{ route('teras.index') }}" class="btn btn-secondary">Balik</a>
        <form action="{{ route('teras.update', $teras) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" name="name" class="form-control" value="{{ $teras->name }}" required>
            </div>
            <div class="form-group">
                <label for="description">Penerangan</label>
                <textarea name="description" class="form-control">{{ $teras->description }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-1">Kemas kini</button>
        </form>
    </div>
</body>
</html>
@endsection