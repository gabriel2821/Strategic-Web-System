@extends('layouts.app')
@section('title')

@section('content')
<a href="{{ route('teras.index') }}" class="btn btn-secondary">Balik Teras</a>
<div class="container mt-4">
    <h1>Arkibkan Data Semasa</h1>
    <form action="{{ route('archives.store') }}" method="POST" onsubmit="return confirm('Adakah anda pasti mahu mengarkib dan menetapkan semula sistem?')">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Arkib Nama</label>
            <input type="text" name="name" id="name" class="form-control" required placeholder="Teras Plan 2020-2025">
        </div>
        <button type="submit" class="btn btn-danger mb-4">📦 Arkib</button>
    </form>
</div>

<hr>

<div class="container mt-4">
    <h2>Arkib Lalu</h2>

    @if ($archives->count())
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
            @foreach ($archives as $archive)
                <div class="col">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="card-title mb-1">{{ $archive->name }}</h5>
                                <p class="card-text">
                                    <small class="text-muted">Created at: {{ $archive->created_at->format('Y-m-d H:i') }}</small>
                                </p>
                            </div>
                            <div class="mt-auto">
                                <a href="{{ route('archives.show', $archive->id) }}" class="btn btn-sm btn-outline-primary w-100">
                                    lihat butiran
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-muted">Tiada arkib tersedia lagi.</p>
    @endif
</div>

@endsection