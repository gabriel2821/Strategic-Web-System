@extends('layouts.app')
@section('title')
@section('content')
    <div class="container">
        <a href="{{ route('teras.index') }}" class="btn btn-secondary">Balik Teras</a>
        <h1>Senarai KRA untuk {{ $teras->name }}</h1>
        <a href="{{ route('langkah.create', $teras) }}" class="btn btn-primary">Tambah KRA</a>
        
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Penerangan</th>
                    <th>Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($langkah as $langkah)
                <tr>
                    <td>{{ $langkah->name }}</td>
                    <td>{{ $langkah->description }}</td>
                    <td>
                        <a href="{{ route('langkah.edit', [$teras, $langkah]) }}" class="btn btn-sm btn-warning">Edit</a>
                        @if (auth()->user()->userType === 'admin')
                        <form action="{{ route('langkah.destroy', [$teras, $langkah]) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Adakah anda pasti anda mahu memadam?')">Padam</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
@endsection